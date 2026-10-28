<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Letter;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\WaxSeal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminStationeryController extends Controller
{
    public function index(): View
    {
        $themes = StationeryTheme::orderBy('order_index')->get();
        $stamps = PostageStamp::orderBy('order_index')->get();
        $seals = WaxSeal::all();

        return view('admin.stationery.index', compact('themes', 'stamps', 'seals'));
    }

    // --- THEMES ---

    public function storeTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'background_css' => ['required', 'string', 'max:255'],
            'text_color' => ['required', 'string', 'max:50'],
            'border_style' => ['nullable', 'string', 'max:255'],
        ]);

        $theme = StationeryTheme::create([
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'name' => $validated['name'],
            'background_css' => $validated['background_css'],
            'text_color' => $validated['text_color'],
            'border_style' => $validated['border_style'],
            'is_active' => true,
            'order_index' => StationeryTheme::max('order_index') + 1,
        ]);

        AdminAuditLog::log('stationery.theme_create', 'StationeryTheme', $theme->id, $theme->toArray());

        return back()->with('status', 'Stationery theme created successfully.');
    }

    public function updateTheme(Request $request, int $id): RedirectResponse
    {
        $theme = StationeryTheme::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'background_css' => ['required', 'string', 'max:255'],
            'text_color' => ['required', 'string', 'max:50'],
            'border_style' => ['nullable', 'string', 'max:255'],
        ]);

        $old = $theme->toArray();
        $theme->update($validated);

        AdminAuditLog::log('stationery.theme_update', 'StationeryTheme', $theme->id, [
            'old' => $old,
            'new' => $theme->toArray(),
        ]);

        return back()->with('status', "Stationery theme '{$theme->name}' updated successfully.");
    }

    public function toggleTheme(int $id): RedirectResponse
    {
        $theme = StationeryTheme::findOrFail($id);
        $theme->update(['is_active' => !$theme->is_active]);

        AdminAuditLog::log('stationery.theme_toggle', 'StationeryTheme', $theme->id, ['is_active' => $theme->is_active]);

        $state = $theme->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Stationery theme '{$theme->name}' has been {$state}.");
    }

    public function destroyTheme(int $id): RedirectResponse
    {
        $theme = StationeryTheme::findOrFail($id);

        // Check if referenced by letters
        if (Letter::where('stationery_theme_id', $theme->id)->exists()) {
            $theme->update(['is_active' => false]);
            AdminAuditLog::log('stationery.theme_soft_retired', 'StationeryTheme', $theme->id);
            return back()->with('status', "Theme is in use by historical letters; it has been deactivated rather than deleted to preserve archives.");
        }

        $theme->delete();
        AdminAuditLog::log('stationery.theme_delete', 'StationeryTheme', $id);

        return back()->with('status', "Theme deleted successfully.");
    }

    // --- STAMPS ---

    public function storeStamp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'theme_category' => ['required', 'string', 'max:100'],
            'image_svg_path' => ['required', 'string', 'max:255'],
        ]);

        $stamp = PostageStamp::create([
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'name' => $validated['name'],
            'theme_category' => $validated['theme_category'],
            'image_svg_path' => $validated['image_svg_path'],
            'is_active' => true,
            'order_index' => PostageStamp::max('order_index') + 1,
        ]);

        AdminAuditLog::log('stationery.stamp_create', 'PostageStamp', $stamp->id, $stamp->toArray());

        return back()->with('status', 'Postage stamp created successfully.');
    }

    public function updateStamp(Request $request, int $id): RedirectResponse
    {
        $stamp = PostageStamp::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'theme_category' => ['required', 'string', 'max:100'],
            'image_svg_path' => ['required', 'string', 'max:255'],
        ]);

        $old = $stamp->toArray();
        $stamp->update($validated);

        AdminAuditLog::log('stationery.stamp_update', 'PostageStamp', $stamp->id, [
            'old' => $old,
            'new' => $stamp->toArray(),
        ]);

        return back()->with('status', "Postage stamp '{$stamp->name}' updated successfully.");
    }

    public function toggleStamp(int $id): RedirectResponse
    {
        $stamp = PostageStamp::findOrFail($id);
        $stamp->update(['is_active' => !$stamp->is_active]);

        AdminAuditLog::log('stationery.stamp_toggle', 'PostageStamp', $stamp->id, ['is_active' => $stamp->is_active]);

        $state = $stamp->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Postage stamp '{$stamp->name}' has been {$state}.");
    }

    public function destroyStamp(int $id): RedirectResponse
    {
        $stamp = PostageStamp::findOrFail($id);

        if (Letter::where('postage_stamp_id', $stamp->id)->exists()) {
            $stamp->update(['is_active' => false]);
            AdminAuditLog::log('stationery.stamp_soft_retired', 'PostageStamp', $stamp->id);
            return back()->with('status', "Postage stamp is referenced by historical letters; it has been deactivated to preserve archives.");
        }

        $stamp->delete();
        AdminAuditLog::log('stationery.stamp_delete', 'PostageStamp', $id);

        return back()->with('status', "Postage stamp deleted successfully.");
    }

    // --- WAX SEALS ---

    public function storeSeal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color_hex' => ['required', 'string', 'max:50'],
            'symbol_svg' => ['required', 'string', 'max:50'],
        ]);

        $seal = WaxSeal::create([
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'name' => $validated['name'],
            'color_hex' => $validated['color_hex'],
            'symbol_svg' => $validated['symbol_svg'],
            'is_active' => true,
        ]);

        AdminAuditLog::log('stationery.seal_create', 'WaxSeal', $seal->id, $seal->toArray());

        return back()->with('status', 'Wax seal created successfully.');
    }

    public function updateSeal(Request $request, int $id): RedirectResponse
    {
        $seal = WaxSeal::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color_hex' => ['required', 'string', 'max:50'],
            'symbol_svg' => ['required', 'string', 'max:50'],
        ]);

        $old = $seal->toArray();
        $seal->update($validated);

        AdminAuditLog::log('stationery.seal_update', 'WaxSeal', $seal->id, [
            'old' => $old,
            'new' => $seal->toArray(),
        ]);

        return back()->with('status', "Wax seal '{$seal->name}' updated successfully.");
    }

    public function toggleSeal(int $id): RedirectResponse
    {
        $seal = WaxSeal::findOrFail($id);
        $seal->update(['is_active' => !$seal->is_active]);

        AdminAuditLog::log('stationery.seal_toggle', 'WaxSeal', $seal->id, ['is_active' => $seal->is_active]);

        $state = $seal->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Wax seal '{$seal->name}' has been {$state}.");
    }

    public function destroySeal(int $id): RedirectResponse
    {
        $seal = WaxSeal::findOrFail($id);

        if (Letter::where('wax_seal_id', $seal->id)->exists()) {
            $seal->update(['is_active' => false]);
            AdminAuditLog::log('stationery.seal_soft_retired', 'WaxSeal', $seal->id);
            return back()->with('status', "Wax seal is attached to archived letters; it has been deactivated rather than deleted.");
        }

        $seal->delete();
        AdminAuditLog::log('stationery.seal_delete', 'WaxSeal', $id);

        return back()->with('status', "Wax seal deleted successfully.");
    }
}

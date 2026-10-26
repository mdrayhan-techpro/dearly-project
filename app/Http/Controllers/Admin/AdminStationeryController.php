<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
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

    public function storeTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'background_css' => ['required', 'string', 'max:255'],
            'text_color' => ['required', 'string', 'max:50'],
            'border_style' => ['nullable', 'string', 'max:255'],
        ]);

        $theme = StationeryTheme::create([
            'slug' => Str::slug($validated['name']),
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

    public function storeStamp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'theme_category' => ['required', 'string', 'max:100'],
            'image_svg_path' => ['required', 'string', 'max:255'],
        ]);

        $stamp = PostageStamp::create([
            'slug' => Str::slug($validated['name']),
            'name' => $validated['name'],
            'theme_category' => $validated['theme_category'],
            'image_svg_path' => $validated['image_svg_path'],
            'is_active' => true,
            'order_index' => PostageStamp::max('order_index') + 1,
        ]);

        AdminAuditLog::log('stationery.stamp_create', 'PostageStamp', $stamp->id, $stamp->toArray());

        return back()->with('status', 'Postage stamp created successfully.');
    }

    public function storeSeal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color_hex' => ['required', 'string', 'max:50'],
            'symbol_svg' => ['required', 'string', 'max:10'],
        ]);

        $seal = WaxSeal::create([
            'slug' => Str::slug($validated['name']),
            'name' => $validated['name'],
            'color_hex' => $validated['color_hex'],
            'symbol_svg' => $validated['symbol_svg'],
            'is_active' => true,
        ]);

        AdminAuditLog::log('stationery.seal_create', 'WaxSeal', $seal->id, $seal->toArray());

        return back()->with('status', 'Wax seal created successfully.');
    }
}

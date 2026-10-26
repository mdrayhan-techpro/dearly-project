@extends('layouts.admin', ['title' => 'Stationery Catalog'])

@section('header', 'Stationery Themes, Postage Stamps & Seals')

@section('content')
<div class="space-y-10">
    <!-- Themes Section -->
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <div>
                <h3 class="font-serif font-bold text-lg text-[#2D2420]">Stationery Paper Themes</h3>
                <p class="text-xs text-[#8A7972]">Archival writing papers available in the Letter Studio.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($themes as $theme)
                <div class="p-4 rounded-lg border shadow-xs text-center" style="background-color: {{ $theme->background_css }}; color: {{ $theme->text_color }};">
                    <span class="font-serif font-bold text-sm block">{{ $theme->name }}</span>
                    <span class="text-[10px] opacity-75 font-mono">{{ $theme->background_css }}</span>
                </div>
            @endforeach
        </div>

        <!-- Add Theme Form -->
        <form method="POST" action="{{ route('admin.stationery.theme_store') }}" class="pt-4 border-t border-[#E0D7D0] grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Theme Name</label>
                <input type="text" name="name" required placeholder="e.g. Amber Autumn" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Background CSS / Hex</label>
                <input type="text" name="background_css" required placeholder="#FDFBF7" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Text Color Hex</label>
                <input type="text" name="text_color" required placeholder="#2B2421" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    + Add Theme
                </button>
            </div>
        </form>
    </div>

    <!-- Stamps Section -->
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <div>
                <h3 class="font-serif font-bold text-lg text-[#2D2420]">Postage Stamps Catalog</h3>
                <p class="text-xs text-[#8A7972]">Collectible digital stamps affixed to outgoing envelopes.</p>
            </div>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-8 gap-4">
            @foreach($stamps as $stamp)
                <div class="postage-stamp p-2 flex flex-col items-center justify-between text-center bg-white shadow-xs rounded">
                    <span class="text-[7px] text-[#B79A61] uppercase">{{ substr($stamp->theme_category, 0, 7) }}</span>
                    <span class="text-xl text-[#783C46] my-1">❦</span>
                    <span class="text-[9px] font-bold text-[#2D2420] truncate w-full">{{ $stamp->name }}</span>
                </div>
            @endforeach
        </div>

        <!-- Add Stamp Form -->
        <form method="POST" action="{{ route('admin.stationery.stamp_store') }}" class="pt-4 border-t border-[#E0D7D0] grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Stamp Name</label>
                <input type="text" name="name" required placeholder="e.g. Heritage Lighthouse" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Category</label>
                <input type="text" name="theme_category" required placeholder="nautical" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Image / SVG Path</label>
                <input type="text" name="image_svg_path" required placeholder="stamps/lighthouse.svg" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    + Add Stamp
                </button>
            </div>
        </form>
    </div>

    <!-- Wax Seals Section -->
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <div>
                <h3 class="font-serif font-bold text-lg text-[#2D2420]">Digital Wax Seals</h3>
                <p class="text-xs text-[#8A7972]">Embossed seals stamped onto folded envelopes.</p>
            </div>
        </div>

        <div class="flex items-center space-x-6">
            @foreach($seals as $seal)
                <div class="text-center space-y-1">
                    <div class="wax-seal w-12 h-12 text-base font-bold shadow-seal mx-auto" style="background-color: {{ $seal->color_hex }};">
                        {{ $seal->symbol_svg ?: 'D' }}
                    </div>
                    <span class="text-[10px] font-bold text-[#2D2420] block">{{ $seal->name }}</span>
                </div>
            @endforeach
        </div>

        <!-- Add Seal Form -->
        <form method="POST" action="{{ route('admin.stationery.seal_store') }}" class="pt-4 border-t border-[#E0D7D0] grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Seal Name</label>
                <input type="text" name="name" required placeholder="e.g. Celestial Violet" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Wax Color Hex</label>
                <input type="text" name="color_hex" required placeholder="#543864" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Emblem Symbol / Char</label>
                <input type="text" name="symbol_svg" required placeholder="✦" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    + Add Wax Seal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

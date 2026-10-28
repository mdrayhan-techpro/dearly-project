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
            <span class="text-xs text-[#8A7972]">{{ $themes->count() }} themes installed</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($themes as $theme)
                <div class="p-4 rounded-lg border shadow-xs relative flex flex-col justify-between h-40 transition-all {{ !$theme->is_active ? 'opacity-60 grayscale-[40%]' : '' }}" style="background-color: {{ $theme->background_css }}; color: {{ $theme->text_color }};">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-serif font-bold text-sm block">{{ $theme->name }}</span>
                            @if($theme->is_active)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#405B4B]/20 text-[#1C2E24]">Active</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-gray-200 text-gray-700">Inactive</span>
                            @endif
                        </div>
                        <span class="text-[10px] opacity-75 font-mono block mt-1">{{ $theme->background_css }}</span>
                    </div>

                    <div class="pt-2 border-t border-black/10 flex items-center justify-between text-[11px]">
                        <button type="button" onclick="editTheme({{ $theme->id }}, '{{ addslashes($theme->name) }}', '{{ addslashes($theme->background_css) }}', '{{ addslashes($theme->text_color) }}', '{{ addslashes($theme->border_style ?? '') }}')"
                            class="font-bold underline hover:opacity-80">
                            Edit
                        </button>
                        <form action="{{ route('admin.stationery.theme_toggle', $theme->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:underline opacity-80">
                                {{ $theme->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.stationery.theme_destroy', $theme->id) }}" method="POST" onsubmit="return confirm('Remove theme {{ $theme->name }}?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-700 hover:underline">
                                &times;
                            </button>
                        </form>
                    </div>
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
            <span class="text-xs text-[#8A7972]">{{ $stamps->count() }} stamps installed</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            @foreach($stamps as $stamp)
                <div class="postage-stamp p-3 flex flex-col items-center justify-between text-center bg-white shadow-xs rounded border border-[#E0D7D0] relative {{ !$stamp->is_active ? 'opacity-50' : '' }}">
                    <div class="flex items-center justify-between w-full text-[8px] text-[#B79A61] uppercase">
                        <span>{{ substr($stamp->theme_category, 0, 7) }}</span>
                        <span>{{ $stamp->is_active ? '✓' : '✕' }}</span>
                    </div>
                    <span class="text-2xl text-[#783C46] my-1">❦</span>
                    <span class="text-[10px] font-bold text-[#2D2420] truncate w-full">{{ $stamp->name }}</span>

                    <div class="mt-2 pt-1 border-t border-[#F0EAE4] flex items-center justify-between w-full text-[9px]">
                        <button type="button" onclick="editStamp({{ $stamp->id }}, '{{ addslashes($stamp->name) }}', '{{ addslashes($stamp->theme_category) }}', '{{ addslashes($stamp->image_svg_path) }}')"
                            class="text-[#783C46] font-bold hover:underline">
                            Edit
                        </button>
                        <form action="{{ route('admin.stationery.stamp_toggle', $stamp->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[#8A7972] hover:text-[#2D2420]">
                                {{ $stamp->is_active ? 'Off' : 'On' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.stationery.stamp_destroy', $stamp->id) }}" method="POST" onsubmit="return confirm('Remove stamp?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">&times;</button>
                        </form>
                    </div>
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
            <span class="text-xs text-[#8A7972]">{{ $seals->count() }} wax seals</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
            @foreach($seals as $seal)
                <div class="text-center p-3 rounded border border-[#E0D7D0] bg-[#FAF8F5] relative {{ !$seal->is_active ? 'opacity-50' : '' }}">
                    <div class="wax-seal w-12 h-12 text-base font-bold shadow-seal mx-auto flex items-center justify-center text-white rounded-full border border-black/20" style="background-color: {{ $seal->color_hex }};">
                        {{ $seal->symbol_svg ?: 'D' }}
                    </div>
                    <span class="text-xs font-bold text-[#2D2420] block mt-2 truncate">{{ $seal->name }}</span>
                    <span class="text-[10px] text-[#8A7972] font-mono block">{{ $seal->color_hex }}</span>

                    <div class="mt-2 pt-2 border-t border-[#E0D7D0] flex items-center justify-between text-[10px]">
                        <button type="button" onclick="editSeal({{ $seal->id }}, '{{ addslashes($seal->name) }}', '{{ addslashes($seal->color_hex) }}', '{{ addslashes($seal->symbol_svg ?? '') }}')"
                            class="text-[#783C46] font-bold hover:underline">
                            Edit
                        </button>
                        <form action="{{ route('admin.stationery.seal_toggle', $seal->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[#8A7972] hover:text-[#2D2420]">
                                {{ $seal->is_active ? 'Off' : 'On' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.stationery.seal_destroy', $seal->id) }}" method="POST" onsubmit="return confirm('Remove wax seal?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">&times;</button>
                        </form>
                    </div>
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

<!-- Edit Theme Modal -->
<div id="editThemeModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Edit Paper Theme</h3>
        <form id="editThemeForm" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Theme Name</label>
                <input type="text" id="themeModalName" name="name" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Background CSS / Hex</label>
                <input type="text" id="themeModalBg" name="background_css" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Text Color Hex</label>
                <input type="text" id="themeModalText" name="text_color" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Border Style (Optional)</label>
                <input type="text" id="themeModalBorder" name="border_style" class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div class="flex justify-end space-x-2 pt-2 border-t border-[#E0D7D0]">
                <button type="button" onclick="document.getElementById('editThemeModal').classList.add('hidden')" class="px-3 py-1.5 text-xs text-[#8A7972]">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-semibold rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Stamp Modal -->
<div id="editStampModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Edit Postage Stamp</h3>
        <form id="editStampForm" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Stamp Name</label>
                <input type="text" id="stampModalName" name="name" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Category</label>
                <input type="text" id="stampModalCategory" name="theme_category" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Image / SVG Path</label>
                <input type="text" id="stampModalPath" name="image_svg_path" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div class="flex justify-end space-x-2 pt-2 border-t border-[#E0D7D0]">
                <button type="button" onclick="document.getElementById('editStampModal').classList.add('hidden')" class="px-3 py-1.5 text-xs text-[#8A7972]">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-semibold rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Seal Modal -->
<div id="editSealModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Edit Wax Seal</h3>
        <form id="editSealForm" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Seal Name</label>
                <input type="text" id="sealModalName" name="name" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Wax Color Hex</label>
                <input type="text" id="sealModalColor" name="color_hex" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Emblem Symbol / Char</label>
                <input type="text" id="sealModalSymbol" name="symbol_svg" required class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2">
            </div>
            <div class="flex justify-end space-x-2 pt-2 border-t border-[#E0D7D0]">
                <button type="button" onclick="document.getElementById('editSealModal').classList.add('hidden')" class="px-3 py-1.5 text-xs text-[#8A7972]">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-semibold rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editTheme(id, name, bg, text, border) {
        document.getElementById('editThemeForm').action = '/admin/stationery/themes/' + id;
        document.getElementById('themeModalName').value = name;
        document.getElementById('themeModalBg').value = bg;
        document.getElementById('themeModalText').value = text;
        document.getElementById('themeModalBorder').value = border;
        document.getElementById('editThemeModal').classList.remove('hidden');
    }

    function editStamp(id, name, category, path) {
        document.getElementById('editStampForm').action = '/admin/stationery/stamps/' + id;
        document.getElementById('stampModalName').value = name;
        document.getElementById('stampModalCategory').value = category;
        document.getElementById('stampModalPath').value = path;
        document.getElementById('editStampModal').classList.remove('hidden');
    }

    function editSeal(id, name, color, symbol) {
        document.getElementById('editSealForm').action = '/admin/stationery/seals/' + id;
        document.getElementById('sealModalName').value = name;
        document.getElementById('sealModalColor').value = color;
        document.getElementById('sealModalSymbol').value = symbol;
        document.getElementById('editSealModal').classList.remove('hidden');
    }
</script>
@endsection

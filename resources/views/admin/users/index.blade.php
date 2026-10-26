@extends('layouts.admin', ['title' => 'User Management'])

@section('header', 'User Directory & Account Controls')

@section('content')
<div class="space-y-6">
    <!-- Filter Toolbar -->
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search by name, nickname, email, or mailing code..."
                class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:ring-[#783C46]/20 px-3 py-2 w-full sm:w-72">

            <select name="role" class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                <option value="">All Roles</option>
                <option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
                <option value="moderator" {{ $role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            </select>

            <select name="status" class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                <option value="">All Statuses</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspended Only</option>
            </select>

            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                Filter
            </button>
            @if($search || $role || $status)
                <a href="{{ route('admin.users.index') }}" class="text-xs text-[#8A7972] hover:text-[#783C46] underline">Clear</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#FAF6EE] border-b border-[#E0D7D0] text-[#8A7972] uppercase text-[10px] tracking-wider font-semibold">
                <tr>
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-4">Nickname</th>
                    <th class="py-3 px-4">Mailing Code</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Joined</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4] text-[#2D2420]">
                @foreach($users as $u)
                    <tr class="hover:bg-[#FAF6EE]/80 transition-colors">
                        <td class="py-3 px-4">
                            <span class="font-bold text-[#2D2420] block">{{ $u->name }}</span>
                            <span class="text-[10px] text-[#8A7972]">{{ $u->email }}</span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-[#783C46]">
                            {{ $u->nickname }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[10px] text-[#B79A61]">
                            {{ $u->mailingCode?->code ?? 'None' }}
                        </td>
                        <td class="py-3 px-4 capitalize">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ in_array($u->role, ['super_admin', 'admin']) ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ str_replace('_', ' ', $u->role) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($u->is_suspended)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">Suspended</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Active</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-[#8A7972]">
                            {{ $u->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.users.show', $u->id) }}" class="text-xs text-[#783C46] font-semibold hover:underline">
                                Inspect &rarr;
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection

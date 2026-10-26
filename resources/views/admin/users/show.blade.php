@extends('layouts.admin', ['title' => 'User Detail: ' . $user->name])

@section('header', 'User Detail & Controls: ' . $user->nickname)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
        <!-- Top Profile Info -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 border-b border-[#E0D7D0] pb-6">
            <div>
                <h2 class="font-serif text-2xl font-bold text-[#2D2420]">{{ $user->name }}</h2>
                <p class="text-xs text-[#8A7972] mt-0.5">Pen-Name: <strong class="text-[#783C46]">{{ $user->nickname }}</strong> • Internal ID #{{ $user->id }}</p>
                <p class="text-xs text-[#8A7972]">Private Email: <span class="font-mono text-[#2D2420]">{{ $user->email }}</span></p>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $user->is_suspended ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $user->is_suspended ? 'Suspended' : 'Active Account' }}
                </span>
                <p class="text-[10px] text-[#8A7972] mt-1">Joined {{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0]">
                <span class="font-serif text-xl font-bold text-[#783C46] block">{{ $sentCount }}</span>
                <span class="text-[10px] uppercase text-[#8A7972]">Letters Sent</span>
            </div>
            <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0]">
                <span class="font-serif text-xl font-bold text-[#405B4B] block">{{ $receivedCount }}</span>
                <span class="text-[10px] uppercase text-[#8A7972]">Letters Received</span>
            </div>
            <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0]">
                <span class="font-serif text-xl font-bold text-[#B79A61] block">{{ $matchesCount }}</span>
                <span class="text-[10px] uppercase text-[#8A7972]">Kindred Matches</span>
            </div>
        </div>

        <!-- Profile Details -->
        @if($user->profile)
            <div class="border-t border-[#E0D7D0] pt-4 space-y-2 text-xs">
                <h4 class="font-serif font-bold text-sm text-[#2D2420]">Profile Metadata</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[#8A7972]">Age:</span> <strong>{{ $user->profile->age }}</strong>
                    </div>
                    <div>
                        <span class="text-[#8A7972]">Language:</span> <strong class="uppercase">{{ $user->profile->preferred_language }}</strong>
                    </div>
                    <div>
                        <span class="text-[#8A7972]">Country:</span> <strong>{{ $user->profile->country ?: 'Unspecified' }}</strong>
                    </div>
                    <div>
                        <span class="text-[#8A7972]">Mailing Code:</span> <strong class="font-mono text-[#783C46]">{{ $user->mailingCode?->code }}</strong>
                    </div>
                </div>
            </div>
        @endif

        <!-- Administrative Controls: Role Modification -->
        <div class="border-t border-[#E0D7D0] pt-6 space-y-4">
            <h4 class="font-serif font-bold text-sm text-[#2D2420]">Role & Permissions</h4>
            <form method="POST" action="{{ route('admin.users.role', $user->id) }}" class="flex items-center space-x-3">
                @csrf
                @method('PUT')
                <select name="role" class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    Update Role
                </button>
            </form>
        </div>

        <!-- Account Suspension Controls -->
        <div class="border-t border-[#E0D7D0] pt-6 space-y-4">
            <h4 class="font-serif font-bold text-sm text-red-800">Account Enforcement</h4>

            @if($user->is_suspended)
                <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-900 space-y-1">
                    <p><strong>Reason for suspension:</strong> {{ $user->suspension_reason }}</p>
                    <p class="text-[10px] text-red-700">Suspended on {{ $user->suspended_at?->format('M d, Y H:i') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.users.reinstate', $user->id) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-green-700 hover:bg-green-800 rounded">
                        Reinstate User Account
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Reason for Suspension</label>
                        <input type="text" name="reason" required placeholder="e.g. Terms violation, abusive language in correspondence..."
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-red-700 hover:bg-red-800 rounded">
                        Suspend Account
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

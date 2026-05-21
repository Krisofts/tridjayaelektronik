@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">User Management</h1>

        <a href="{{ route('users.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">
            + Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Groups</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>

                        {{-- GROUP FIX FINAL --}}
                        <td class="p-3">
                            @forelse($user->getGroups() as $groupKey)
                                @php
                                    $group = config('auth_group.groups')[$groupKey] ?? null;
                                @endphp

                                @if($group)
                                    <span class="px-2 py-1 text-xs bg-gray-200 rounded mr-1">
                                        {{ $group['title'] }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 rounded mr-1">
                                        {{ $groupKey }}
                                    </span>
                                @endif
                            @empty
                                <span class="text-gray-400 text-xs">No Group</span>
                            @endforelse
                        </td>

                        <td class="p-3">
                            @if($user->active)
                                <span class="text-green-600 font-semibold">Active</span>
                            @else
                                <span class="text-red-600 font-semibold">Inactive</span>
                            @endif
                        </td>

                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('users.edit', $user) }}"
                               class="px-3 py-1 bg-yellow-500 text-white rounded">
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="px-3 py-1 bg-red-600 text-white rounded">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Tidak ada data user
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
@endsection
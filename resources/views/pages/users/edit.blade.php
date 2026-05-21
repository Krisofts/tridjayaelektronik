@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Edit User</h1>

    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <input type="text" name="name"
               value="{{ $user->name }}"
               class="w-full border p-2 rounded" required>

        <input type="email" name="email"
               value="{{ $user->email }}"
               class="w-full border p-2 rounded" required>

        <input type="password" name="password"
               placeholder="Kosongkan jika tidak diubah"
               class="w-full border p-2 rounded">

        <label class="flex items-center gap-2">
            <input type="checkbox" name="active" value="1"
                   {{ $user->active ? 'checked' : '' }}>
            Active
        </label>

        {{-- GROUP FINAL CHECKED --}}
        <div>
            <label class="block mb-2 font-semibold">Groups</label>

            <div class="border p-3 rounded space-y-2">
                @forelse($groups as $key => $group)
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="groups[]"
                               value="{{ $key }}"
                               {{ in_array($key, $userGroups ?? []) ? 'checked' : '' }}>

                        <span>{{ $group['title'] }}</span>
                    </label>
                @empty
                    <span class="text-gray-400">No groups available</span>
                @endforelse
            </div>
        </div>

        <button class="w-full bg-yellow-500 text-white py-2 rounded">
            Update
        </button>
    </form>

</div>
@endsection
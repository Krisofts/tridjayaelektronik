@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Tambah User</h1>

    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Nama"
               class="w-full border p-2 rounded" required>

        <input type="email" name="email" placeholder="Email"
               class="w-full border p-2 rounded" required>

        <input type="password" name="password" placeholder="Password"
               class="w-full border p-2 rounded" required>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="active" value="1">
            Active
        </label>

        {{-- GROUP FINAL --}}
        <div>
            <label class="block mb-2 font-semibold">Groups</label>

            <div class="border p-3 rounded space-y-2">
                @forelse($groups as $key => $group)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="groups[]" value="{{ $key }}">
                        <span>{{ $group['title'] }}</span>
                    </label>
                @empty
                    <span class="text-gray-400">No groups available</span>
                @endforelse
            </div>
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Simpan
        </button>
    </form>

</div>
@endsection
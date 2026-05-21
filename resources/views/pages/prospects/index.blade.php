@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

    {{-- MY PROSPECT ALL TIME --}}
    <div class="bg-green-500 text-white p-4 rounded shadow">
        <div class="text-sm">My Prospects (All Time)</div>
        <div class="text-2xl font-bold">
            {{ $myProspectsAllTime }}
        </div>
    </div>

    {{-- MY PROSPECT THIS MONTH --}}
    <div class="bg-purple-500 text-white p-4 rounded shadow">
        <div class="text-sm">My Prospects (This Month)</div>
        <div class="text-2xl font-bold">
            {{ $myProspectsThisMonth }}
        </div>
    </div>

</div>

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Prospect List</h2>

        <a href="{{ route('prospects.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded">
            + Add Prospect
        </a>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Phone</th>
                    <th class="p-3 text-left">Source</th>
                    <th class="p-3 text-left">Interest</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($prospects as $p)
                    <tr class="border-t">
                        <td class="p-3">{{ $p->name }}</td>
                        <td class="p-3">{{ $p->phone }}</td>
                        <td class="p-3">{{ $p->source }}</td>
                        <td class="p-3">{{ $p->interest_of }}</td>
                        <td class="p-3">{{ $p->status }}</td>
                        <td class="p-3">{{ $p->user->name ?? '-' }}</td>

                        <td class="p-3 flex gap-2">
                            <a href="{{ route('prospects.edit', $p) }}"
                               class="text-blue-600">Edit</a>

                            <form action="{{ route('prospects.destroy', $p) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600"
                                        onclick="return confirm('Delete this data?')">
                                    Delete
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $prospects->links() }}
    </div>

</div>
@endsection
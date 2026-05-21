@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">

    <h2 class="text-xl font-bold mb-4">Edit Prospect</h2>

    <form method="POST"
          action="{{ route('admin.prospects.update', $prospect) }}">
        @csrf
        @method('PUT')

        <div class="space-y-3">

            <input name="name"
                   value="{{ $prospect->name }}"
                   class="w-full border p-2 rounded">

            <input name="phone"
                   value="{{ $prospect->phone }}"
                   class="w-full border p-2 rounded">

            <textarea name="address"
                      class="w-full border p-2 rounded">{{ $prospect->address }}</textarea>

            <input name="source"
                   value="{{ $prospect->source }}"
                   class="w-full border p-2 rounded">

            <input name="interest_of"
                   value="{{ $prospect->interest_of }}"
                   class="w-full border p-2 rounded">

            <input name="status"
                   value="{{ $prospect->status }}"
                   class="w-full border p-2 rounded">

            <input name="payment_method"
                   value="{{ $prospect->payment_method }}"
                   class="w-full border p-2 rounded">

            <textarea name="notes"
                      class="w-full border p-2 rounded">{{ $prospect->notes }}</textarea>

            <input type="datetime-local"
                   name="follow_up_at"
                   class="w-full border p-2 rounded"
                   value="{{ $prospect->follow_up_at }}">

            <button class="bg-green-500 text-white px-4 py-2 rounded">
                Update
            </button>

        </div>
    </form>

</div>
@endsection
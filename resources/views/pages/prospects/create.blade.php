@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">

    <h2 class="text-xl font-bold mb-4">Add Prospect</h2>

    <form method="POST" action="{{ route('prospects.store') }}">
        @csrf

        <div class="space-y-3">

            <input name="name" placeholder="Name"
                   class="w-full border p-2 rounded">

            <input name="phone" placeholder="Phone"
                   class="w-full border p-2 rounded">

            <textarea name="address" placeholder="Address"
                      class="w-full border p-2 rounded"></textarea>

            <input name="source" placeholder="Source"
                   class="w-full border p-2 rounded">

            <input name="interest_of" placeholder="Interest Of"
                   class="w-full border p-2 rounded">

            <input name="status" value="new"
                   class="w-full border p-2 rounded">

            <input name="payment_method" placeholder="Payment Method"
                   class="w-full border p-2 rounded">

            <textarea name="notes" placeholder="Notes"
                      class="w-full border p-2 rounded"></textarea>

            <input type="datetime-local" name="follow_up_at"
                   class="w-full border p-2 rounded">

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Save
            </button>

        </div>
    </form>

</div>
@endsection
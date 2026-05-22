@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- ===================== TOP SECTION ===================== --}}
    <div class="col-span-12 xl:col-span-7 space-y-6">

        {{-- Today vs Yesterday Sales --}}
        <x-overview.today-vs-yesterday />

    </div>

    <div class="col-span-12 xl:col-span-5">

        {{-- Monthly Target (placeholder) --}}
        <div class="rounded-xl border border-gray-200 p-4 bg-white shadow-sm">
            <p class="text-sm text-gray-500">Monthly Target</p>
            <p class="text-xs text-gray-400 mt-2">Component coming soon...</p>
        </div>

    </div>

    {{-- ===================== MIDDLE SECTION ===================== --}}
    <div class="col-span-12">

        <div class="rounded-xl border border-gray-200 p-6 bg-white shadow-sm">
            <p class="text-sm text-gray-500">Yearly Sales By Month</p>
            <p class="text-xs text-gray-400 mt-2">Component coming soon...</p>
        </div>

    </div>

    {{-- ===================== BOTTOM SECTION ===================== --}}
    <div class="col-span-12 xl:col-span-5">

        <div class="rounded-xl border border-gray-200 p-4 bg-white shadow-sm">
            <p class="text-sm text-gray-500">Top Sales Employee</p>
            <p class="text-xs text-gray-400 mt-2">Component coming soon...</p>
        </div>

    </div>

    <div class="col-span-12 xl:col-span-7">

        <div class="rounded-xl border border-gray-200 p-4 bg-white shadow-sm">
            <p class="text-sm text-gray-500">Branch Performance</p>
            <p class="text-xs text-gray-400 mt-2">Component coming soon...</p>
        </div>

    </div>

</div>
@endsection
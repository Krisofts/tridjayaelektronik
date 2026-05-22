@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- ===================== TOP SECTION ===================== --}}
    <div class="col-span-12 xl:col-span-7 space-y-6">

        {{-- Today vs Yesterday Sales --}}
        <x-overview.today-vs-yesterday />

    </div>

    <div class="col-span-12 xl:col-span-5">

        <x-overview.monthly-target />

    </div>

    {{-- ===================== MIDDLE SECTION ===================== --}}
    <div class="col-span-12">

         <x-overview.daily-dealer-target/>

    </div>

    {{-- ===================== BOTTOM SECTION ===================== --}}
    <div class="col-span-12 xl:col-span-5">

         <x-overview.top-sales/>
    </div>

    <div class="col-span-12 xl:col-span-7">

        <x-overview.dealer-sales/>

    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- TOP LEFT: TODAY SALES --}}
    <div class="col-span-12 xl:col-span-7 space-y-6">

        <x-overview.today-sales :comparison="$comparison" />
        <x-ecommerce.monthly-sale :monthly="$monthly" />

    </div>

    {{-- TOP RIGHT: MONTHLY TARGET --}}
    <div class="col-span-12 xl:col-span-5">

        <x-overview.monthly-target :monthly="$monthly" />

    </div>

    {{-- FULL WIDTH: MONTHLY ALL BRANCH --}}
    <div class="col-span-12">

        

    </div>

    {{-- BOTTOM LEFT: TOP SALES EMPLOYEE --}}
    <div class="col-span-12 xl:col-span-5">

        <x-overview.top-sales-employees :employees="$topSalesEmployees" />

    </div>

    {{-- BOTTOM RIGHT --}}
    <div class="col-span-12 xl:col-span-7">

        {{-- widget tambahan (trend / chart / activity) --}}
<x-overview.monthly-sales-all-branch :branches="$monthlyByDealer" />
    </div>

</div>
@endsection
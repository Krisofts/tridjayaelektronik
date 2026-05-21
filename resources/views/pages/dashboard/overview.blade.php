@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- TOP LEFT: TODAY SALES --}}
    <div class="col-span-12 xl:col-span-7 space-y-6">

        

     <x-overview.today-vs-yesterday />
       

    </div>

    {{-- TOP RIGHT: MONTHLY TARGET --}}
    <div class="col-span-12 xl:col-span-5">
<x-overview.monthly-target />
        

    </div>

    {{-- FULL WIDTH: YEARLY SALES BY MONTH --}}
    <div class="col-span-12">

      

            
    </div>

    {{-- BOTTOM LEFT: TOP SALES EMPLOYEE --}}
    <div class="col-span-12 xl:col-span-5">

       <x-overview.top-sales />

    </div>

    {{-- BOTTOM RIGHT: MONTHLY SALES ALL BRANCH --}}
    <div class="col-span-12 xl:col-span-7">

       <x-overview.dealer-sales />

    </div>

</div>
@endsection
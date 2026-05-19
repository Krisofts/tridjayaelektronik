@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="User Profile" />

<div class="py-10">
    <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">

        <!-- PROFILE INFO -->
        
            @include('profile.partials.update-profile-information-form')
        

        <!-- PASSWORD -->
       
            @include('profile.partials.update-password-form')
        

        <!-- DELETE ACCOUNT -->
        
            @include('profile.partials.delete-user-form')
        

    </div>
</div>

@endsection
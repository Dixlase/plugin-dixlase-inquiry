@extends('layouts.admin')

@section('title', __('dixlase-inquiry::admin.inquiry.title'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('dixlase-inquiry::admin.inquiry.title') }}
        </h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <p class="text-gray-600 dark:text-gray-400">
            {{ __('dixlase-inquiry::admin.inquiry.list_coming_soon') }}
        </p>
    </div>
</div>
@endsection

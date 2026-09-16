@extends('layouts.app')

@section('title', 'الملف الشخصي | متجري')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">الملف الشخصي</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">إدارة بياناتك وكلمة المرور</p>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border p-5 md:p-6"
                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-xl border p-5 md:p-6"
                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-xl p-5 md:p-6" style="background-color: var(--bg-primary); border: 1px solid #fecaca;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>

@endsection
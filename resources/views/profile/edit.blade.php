@extends('layouts.app')

@section('title', 'الملف الشخصي | متجري')

@section('content')

    <div class="container-narrow pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8"
                aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">الملف الشخصي</span>
            </nav>

            <span class="eyebrow block mb-4">— حسابك</span>
            <h1 class="display-2 mb-4 text-balance">
                إدارة
                <span class="text-forest dark:text-gold">بياناتك</span>
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                حدّث معلوماتك الشخصية وكلمة المرور، أو احذف حسابك.
            </p>
        </header>

        {{-- ════════════ SECTIONS ════════════ --}}
        <div class="space-y-6">

            {{-- Section 01 — Profile Info --}}
            <section class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-stone-200 dark:border-stone-800">
                    <span
                        class="w-7 h-7 flex items-center justify-center bg-forest dark:bg-gold text-cream dark:text-ink font-display font-bold text-xs"
                        style="border-radius: 4px;">01</span>
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                        المعلومات الشخصية
                    </span>
                </div>

                <div class="p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            {{-- Section 02 — Password --}}
            <section class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-stone-200 dark:border-stone-800">
                    <span
                        class="w-7 h-7 flex items-center justify-center bg-forest dark:bg-gold text-cream dark:text-ink font-display font-bold text-xs"
                        style="border-radius: 4px;">02</span>
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                        كلمة المرور
                    </span>
                </div>

                <div class="p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            {{-- Section 03 — Danger Zone --}}
            <section class="border-2 border-red-200 dark:border-red-900/50" style="border-radius: 4px;">
                <div
                    class="flex items-center gap-3 px-8 py-6 border-b border-red-200 dark:border-red-900/50 bg-red-50/50 dark:bg-red-950/20">
                    <span
                        class="w-7 h-7 flex items-center justify-center bg-red-600 text-white font-display font-bold text-xs"
                        style="border-radius: 4px;">03</span>
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-red-700 dark:text-red-400">
                        منطقة الخطر
                    </span>
                </div>

                <div class="p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>

        </div>

    </div>

@endsection
@php
    $closure = app(\App\Services\ClosurePeriod::class);
@endphp

<div role="status"
     {{ $attributes->merge(['class' => 'max-w-4xl mx-4 sm:mx-auto mb-8 rounded-xl border-l-8 border-amber-500 bg-amber-100 text-amber-950 shadow-lg dark:bg-amber-900/60 dark:border-amber-400 dark:text-amber-50']) }}>
    <div class="px-5 py-5 sm:px-8 sm:py-6 flex items-start gap-4 sm:gap-6">
        <i class="fa-solid fa-calendar-xmark text-3xl sm:text-5xl text-amber-600 dark:text-amber-300 shrink-0" aria-hidden="true"></i>
        <div class="flex-1">
            <p class="text-xl sm:text-2xl font-bold mb-2">
                {{ __('closure.title') }}
            </p>
            <p class="text-base sm:text-lg leading-relaxed">
                {{ __('closure.banner', [
                    'start' => $closure->start()->translatedFormat('d/m/Y'),
                    'end' => $closure->end()->translatedFormat('d/m/Y'),
                    'reopen' => $closure->reopenDate()->translatedFormat('d/m/Y'),
                    'reopen_short' => $closure->reopenDate()->translatedFormat('d/m'),
                ]) }}
            </p>
        </div>
    </div>
</div>

<x-layout>
    <x-slot name="title">Accueil</x-slot>
    @if(app(\App\Services\ClosurePeriod::class)->isNoticeActive())
        <x-closure-banner class="mt-6" />
    @endif
    @include('home.sections.title')
{{--    @include('home.sections.information')--}}
    @include('home.sections.commader')
    @include('home.sections.productsdigital')


{{--    @include('home.sections.hero')--}}
    @include('home.sections.objectif')
    @include('home.sections.donation')
    @include('home.sections.membre')
    @include('home.sections.testimonial')
    @include('home.sections.subscribe')
</x-layout>

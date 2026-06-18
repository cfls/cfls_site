<div class="max-w-screen-2xl mx-auto px-4 py-12 space-y-8">

    @if($data->image_event)
        <img src="{{ asset('storage/' . $data->image_event) }}"
             alt="{{ $data->name }}"
             class="mx-auto mb-8 w-full h-auto object-contain "
             loading="lazy"/>

    @else
        <!-- Main Title -->
        <h2 class="responsive-title text-xl sm:text-4xl md:text-6xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center mb-10">
            {{ $data->name }}
        </h2>
    @endif


    <!-- Main Description -->
    <div class="lg:text-2xl font-light text-gray-800 dark:text-white text-center">
        {!! $data->description !!}
    </div>
</div>

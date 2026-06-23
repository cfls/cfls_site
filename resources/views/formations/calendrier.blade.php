<x-layout>
  <x-slot name="title">{{ $formation->bottom }}</x-slot>
  <x-menuformation :slug="$slug" />

  <h2 class="mb-8 text-7xl tracking-tight font-extrabold text-gray-900 dark:text-white text-center">{{ $formation->buttom }}</h2>
  <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">

  @foreach($calendars as $calendar)

          <a
              @if($calendar->quota)
                  href={{ route('formation', ['slug' => $slug ,'formation' => $calendar->slug, 'id'=>$calendar->id]) }}
              @endif
              class="flex flex-col items-center rounded-lg border border-gray-200 bg-gray-200 px-4 py-6 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700
             {{ !$calendar->quota ? 'cursor-not-allowed  opacity-50 pointer-events-auto' : 'opacity-100 pointer-events-auto' }}"
          >
        <img src="{{ asset('storage/'. $calendar->image) }}" alt="placeholder"  class="w-full max-w-[150px] sm:max-w-[200px] h-auto object-cover rounded-lg mb-4">

          <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
              {{
                  str_replace(
                      ['Formations accélérées', 'Formations à l\'année'], // buscar
                      ['Formation accélérée', 'Formation à l\'année'],       // reemplazar
                      $formation->title
                  )
              }}
              - {{ $calendar->levels->name }}
          </h5>
        <p class="font-normal text-gray-700 dark:text-gray-400 mb-3">Niveau  {{$calendar->levels->level}}, du {{ \Carbon\Carbon::parse($calendar->start_date)->format('d/m/Y') }}
            au {{ \Carbon\Carbon::parse($calendar->end_date)->format('d/m/Y') }}
             @if($calendar->day) ({{$calendar->day}}) @endif </p>
        <p class="font-normal text-gray-700 dark:text-gray-400 mb-3">Prix : {{$calendar->price}} €</p>
        <div
            class="text-white bg-csfl hover:bg-cyan-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Inscription</div>
    </a>
    @endforeach

</div>
</x-layout>

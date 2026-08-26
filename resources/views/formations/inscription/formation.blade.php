<x-layout>
    <x-slot name="title">{{ $slug }}</x-slot>
    <x-menuformation :slug="$slug"/>
    <div class="grid max-w-screen-2xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1
                class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                Vos coordonnées
            </h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Vous
                avez
                déjà un compte ? Connectez-vous pour réserver plus rapidement.
            </p>
            <p class="max-w-2xl mb-2  font-semibold lg:mb-4 md:text-lg lg:text-xl dark:text-gray-400">
                Détails de la réservation
            </p>
            <p class="max-w-2xl  font-semibold  md:text-lg lg:text-xl dark:text-gray-400 ">

                {{
                  str_replace(
                      ['Formations accélérées', 'Formations à l\'année'], // buscar
                      ['Formation accélérée', 'Formation à l\'année'],       // reemplazar
                      $formation->title
                  )
              }} - {{ $inscription->levels->name }}
            </p>
            <p class="max-w-2xl  font-semibold  md:text-lg lg:text-xl dark:text-gray-400 ">
                Commence: {{ \Carbon\Carbon::parse($inscription->start_date)->format('d/m/Y') }}
                à {{ \Carbon\Carbon::createFromFormat('H:i', $inscription->hour_start)->format('g:i A') }}
            </p>
            <p class="max-w-2xl  font-semibold  md:text-lg lg:text-xl dark:text-gray-400 ">
                Avenue du four à briques, 3A - 1140 Evere ( Bruxelles)
            </p>
{{--            <p class="max-w-2xl  font-semibold  md:text-lg lg:text-xl dark:text-gray-400 ">--}}
{{--                @php--}}


{{--                    $start = \Carbon\Carbon::parse($inscription->start_date);--}}
{{--                    $end = \Carbon\Carbon::parse($inscription->end_date);--}}
{{--                    $totalDays = $start->diffInDays($end) + 1;--}}
{{--                @endphp--}}
{{--                {{$totalDays}} séances au total--}}
{{--            </p>--}}

            <p class="max-w-2xl mb-2  font-semibold lg:mb-4 md:text-lg lg:text-xl dark:text-gray-400 mt-3">
                Détails du paiement <span class="text-blue-500"> {{ $inscription->price }} €</span>
            </p>
            <div>
                @if(session('success'))
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-show="show"
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500 ease-in-out"
                        role="alert"
                    >
                        <strong class="font-bold">Succès !</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-show="show"
                        class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500 ease-in-out"
                        role="alert"
                        >
                        <strong class="font-bold">Information !</strong>
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                  @endif
                    @auth
                        @php $readonly = 'readonly'; @endphp
                    @else
                        @php $readonly = ''; @endphp
                    @endauth
                    @php
                        $nameParts = explode(' ', auth()->user()->name ?? '');
                        $firstName = $nameParts[0] ?? '';
                        $lastName = count($nameParts) > 1 ? $nameParts[1] : '';
                    @endphp
                <form class="max-w-md mx-auto" method="POST"
                      action="{{route('inscription.formation', ['id' => $inscription->id])}}">
                    @csrf
                    <div x-data="{ email: '{{ old('email', auth()->user()->email ?? '') }}', error: @json($errors->has('email')) }"
                         class="relative z-0 w-full mb-5 group">
                        <input type="email"
                               name="email"
                               id="email"
                               x-model="email"
                               @input="error = false"
                               value="{{ old('email', auth()->user()->email ?? '') }}"
                               {{ $readonly }}
                               class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                               autocomplete="off"
                        />
                        <label for="email"
                               class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Adresse
                            e-mail</label>
                        <template x-if="error">
                            <p class="mt-2 text-sm camp">
                                {{ $errors->first('email') }}
                            </p>
                        </template>

                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div x-data="{ first_name: '{{ old('first_name',$firstName ?? '') }}', error: @json($errors->has('first_name')) }"
                             class="relative z-0 w-full mb-5 group">
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   x-model="first_name"
                                   @input="error = false"
                                   value="{{ old('first_name', $firstName) }}"
                                   {{ $readonly }}
                                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                   autocomplete="off"
                            />
                            <label for="first_name"
                                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Prénom</label>
                            <template x-if="error">
                                <p class="mt-2 text-sm camp">
                                    {{ $errors->first('first_name') }}
                                </p>
                            </template>

                        </div>


                        <div x-data="{ last_name: '{{ old('last_name', $lastName) }}', error: @json($errors->has('last_name')) }"
                             class="relative z-0 w-full mb-5 group">
                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   x-model="last_name"
                                   @input="error = false"
                                   value="{{ old('last_name', $lastName) }}"
                                   {{ $readonly }}
                                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                                  border-gray-300 appearance-none dark:text-white dark:border-gray-600
                                  dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                   autocomplete="off"
                            />
                            <label for="last_name"
                                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300
                  transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                  peer-focus:scale-75 peer-focus:-translate-y-6">
                                Nom de famille
                            </label>
                            <template x-if="error">
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                                    {{ $errors->first('last_name') }}
                                </p>
                            </template>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">

                        <div x-data="{ phone: '{{ old('phone', auth()->user()->telephone ?? '') }}', error: @json($errors->has('phone')) }"
                             class="relative z-0 w-full mb-5 group">
                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                x-model="phone"
                                @input="error = false"
                                value="{{ old('phone', auth()->user()->telephone ?? '') }}"

                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                               border-gray-300 appearance-none dark:text-white dark:border-gray-600
                               dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                autocomplete="off"

                            />
                            <label for="phone"
                                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300
                                  transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0
                                  peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100
                                  peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Numéro de téléphone
                            </label>
                            <template x-if="error">
                                <p class="mt-2 text-sm camp">
                                    {{ $errors->first('phone') }}
                                </p>
                            </template>
                        </div>

                    </div>
                    <div class="flex items-start mb-5">
                        <div class="flex items-center h-5">
                            <input
                                id="reduit_rate"
                                name="reduit_rate"
                                type="checkbox"
                                value="1"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-0 dark:bg-gray-700 dark:border-gray-600"
                            >
                        </div>
                        <div class="ms-2 text-sm">
                            <label for="reduit_rate" class="font-medium text-gray-900 dark:text-white">
                                Je bénéficie d'un tarif réduit
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                (chômeur ou étudiant) — preuve à fournir le 1er jour
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start mb-5">
                        <div class="flex items-center h-5">
                            <input
                                id="newsletter"
                                name="newsletter"
                                type="checkbox"
                                value="1"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-0 dark:bg-gray-700 dark:border-gray-600"
                            >
                        </div>
                        <div class="ms-2 text-sm">
                            <label for="newsletter" class="font-medium text-gray-900 dark:text-white">
                                Je souhaite recevoir la <strong>newsletter</strong> de CFLS par e-mail.
                            </label>
                        </div>
                    </div>
                    <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Réserver
                    </button>
                </form>
            </div>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="{{ asset('storage/'.$inscription->image) }}" alt="mockup">
        </div>
    </div>
</x-layout>

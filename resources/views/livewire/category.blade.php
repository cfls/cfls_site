<div
    x-data="{ isOpen: false, selectedProductId: null }"
    @open-slide-over.window="
        isOpen = true;
        selectedProductId = $event.detail?.id;
    "
    @close="isOpen = false"
>



    <form class="max-w-sm mx-auto justify-end mb-6">
        <label for="products" class="block mb-2 font-medium text-gray-900 dark:text-white text-3xl text-center mb-5">
            Sélectionner le produit
        </label>
        <select id="products" wire:model.live="selectedCategory"
                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-base">

            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </form>
    @if (!empty($groupedProducts))
        <div class="flex justify-center flex-wrap gap-4 my-6">
            @foreach ($groupedProducts as $subcategoryName => $products)
                <a href="#{{ Str::slug($subcategoryName) }}"
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition uppercase">
                    {{ $subcategoryName }}
                </a>
            @endforeach
        </div>
    @endif
    @if (!empty($groupedProducts))
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            @foreach ($groupedProducts as $subcategoryName => $products)
                <div class="mb-6">
                    <h2 id="{{ Str::slug($subcategoryName) }}"
                        class="text-center uppercase font-extrabold tracking-tight text-gray-900 md:text-2xl  mb-8
                    text-3xl sm:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl dark:text-white">
                        {{ $subcategoryName }}
                    </h2>

                    <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">

                        @foreach ($products as $product)

                            <div
                                x-data="{
                                    choiceValue: '0',
                                }"
                                @class([
                                    'rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800',
                                    'block' => $product->status == 1 || $product->status == 2 || $product->status == 3,
                                    'hidden' => $product->status == 0,

                                ])>

                                @if ($product->status == 2)
                                    <div class="relative">
                                        <div class="absolute -top-10 -left-12 ">
                                            <img src="{{ asset('img/new.png') }}" alt="Sold Out" class="w-48">
                                        </div>
                                    </div>
                                @elseif ($product->status == 3)
                                    <div class="relative">
                                        <div class="absolute -top-10 -left-12 ">
                                            <img src="{{ asset('img/soldout.png') }}" alt="Sold Out" class="w-48 ">
                                        </div>
                                    </div>
                                @endif
                                <div class="h-56 w-full">
                                    <a wire:navigate href="{{route('boutique.detail', ['slug' => $product->slug])}}">
                                        @if ($product->images->isNotEmpty())
                                            @php
                                                $image = $product->images->first();
                                            @endphp
                                            <img @class(['mx-auto h-full object-cover',
                                                          'opacity-50' => $product->status == 3,
                                                          'z-1' => $product->status == 3,
                                                      ])
                                                 src="{{ asset('storage/' . $image->image_path) }}"
                                                 alt="{{ $product->name }}"/>
                                        @endif
                                    </a>
                                </div>

                                <div class="pt-6">
                                    <div>
                                        <a wire:navigate href="{{route('boutique.detail', ['slug' => $product->slug])}}"
                                           class="text-2xl font-semibold leading-tight text-gray-900 hover:underline dark:text-white line-clamp-2 min-h-[3rem] block overflow-hidden">
                                            {{$product->name}}
                                        </a>
                                    </div>



                                        <div class="space-y-4 mt-2 @if($product->options->isEmpty()) hidden @endif">
                                            <label for="choice"
                                                   class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sélectionner:</label>
                                            <div class="flex items-center gap-2">
                                                <select
                                                    x-ref="choice"
                                                    x-model="choiceValue"
                                                    @change="if (choiceValue !== '0') console.log('✅ Option selected:', choiceValue)"
                                                    id="choice"
                                                    name="choice"
                                                    class="border rounded px-4 py-2 w-full sm:w-64 text-base bg-white dark:bg-gray-800 dark:text-white"
                                                >
                                                    <option value="0" selected disabled>Choisissez une option</option>
                                                    @foreach($product->options as $option)
                                                        <option value="{{ $option->option_name }}">{{ $option->option_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                    <div class="mt-3 flex items-center justify-between gap-4 min-h-[50px]">
                                    <span
                                        class="text-2xl font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                        {{$product->price}} €
                                    </span>

                                        @if ($product->status == 3)
                                            <button
                                                class="w-full bg-gray-400 text-white py-3 rounded-lg p-2 text-center cursor-not-allowed opacity-50"
                                                disabled>
                                                Indisponible
                                            </button>
                                        @else
                                            <button
                                                class="w-full bg-csfl text-white py-3 rounded-lg p-2 text-center"
                                                @click="
                                                    window.dispatchEvent(new CustomEvent('add-to-cart', {
                                                        detail: {
                                                            id: {{ $product->id }},
                                                            quantity: 1,
                                                            choix: choiceValue,

                                                        }
                                                    }));
                                                "
                                              >
                                                 Ajouter au panier
                                            </button>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <!-- Botón TOP -->
    <x-top/>


</div>

<x-layout>
    <x-slot name="title">Paiement</x-slot>
    @php
        $nameParts = $user ? explode(' ', $user->name) : ['', ''];
    @endphp

    @if (count($cart) === 0)
        <div class="text-center text-xl text-red-600 font-semibold py-10">
            Votre panier est vide.
        </div>
    @else
        @auth
            <form x-data="checkout()"
                  x-init="init()"
                  @submit.prevent="confirmerAchat"
                  class="container mx-auto px-4 py-8"
                  enctype="multipart/form-data">

                <!-- Notification -->
                <div x-show="notification"
                     x-cloak
                     x-transition
                     :class="notificationType === 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                     class="fixed top-4 right-4 z-50 px-4 py-2 rounded shadow flex items-center space-x-2">
                    <span x-text="notification"></span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Colonne Gauche -->
                    <div class="md:col-span-2 space-y-8">
                        <!-- Informations Personnelles -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h2 class="text-3xl font-semibold mb-4 dark:text-white">
                                Informations Personnelles
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Prénom -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Prénom
                                    </label>
                                    <input type="text"
                                           name="first_name"
                                           placeholder="Prénom"
                                           value="{{ $nameParts[0] ?? '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white"
                                           :class="errors.first_name ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.first_name">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>
                                </div>

                                <!-- Nom -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Nom
                                    </label>
                                    <input type="text"
                                           name="second_name"
                                           placeholder="Nom"
                                           value="{{ $nameParts[1] ?? '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white"
                                           :class="errors.second_name ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.second_name">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Adresse E-mail
                                    </label>
                                    <input type="email"
                                           name="email"
                                           placeholder="Adresse E-mail"
                                           value="{{ isset($user) ? $user->email : '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white"
                                           :class="errors.email ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.email">
                                        <p class="mt-1 text-sm camp">Veuillez entrer une adresse email valide</p>
                                    </template>
                                </div>

                                <!-- Téléphone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Téléphone
                                    </label>
                                    <input type="tel"
                                           name="telephone"
                                           placeholder="Téléphone"
                                           value="{{ isset($user) ? $user->telephone : '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white"
                                           :class="errors.telephone ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.telephone">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>
                                </div>

                                <!-- Société -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Nom de la société
                                    </label>
                                    <input type="text"
                                           name="society"
                                           placeholder="society"
                                           value="{{ isset($user) ? $user->society : '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                                </div>

                            </div>
                        </div>

                        <!-- Mode de Livraison -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h2 class="text-3xl font-semibold mb-4 dark:text-white">
                                Mode de Livraison
                            </h2>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <input type="radio"
                                           x-model="delivery"
                                           value="retrait"
                                           id="pickup"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600
                                                  dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="pickup"
                                           class="text-lg font-medium text-gray-700 dark:text-white cursor-pointer">
                                        Retrait sur place (Gratuit)
                                    </label>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="radio"
                                           x-model="delivery"
                                           value="livraison"
                                           id="delivery"
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600
                                                  dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="delivery"
                                           class="text-lg font-medium text-gray-700 dark:text-white cursor-pointer">
                                        Livraison à domicile
                                        <span class="text-sm text-gray-500 dark:text-gray-400" x-show="delivery === 'livraison'">
                                            (+<span x-text="deliveryFee.toFixed(2)"></span> €)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Infos Livraison -->
                        <div x-show="delivery === 'livraison'"
                             x-transition
                             class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h2 class="text-3xl font-semibold mb-4 dark:text-white">
                                Informations de livraison
                            </h2>

                            <div class="space-y-4">
                                <!-- Adresse -->
                                <div>
                                    <label for="address"
                                           class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Adresse complète
                                    </label>
                                    <input id="address"
                                           name="address"
                                           type="text"
                                           placeholder="Adresse complète"
                                           value="{{ isset($user) ? $user->address : '' }}"
                                           class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"
                                           :class="errors.address ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.address">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>
                                </div>
                                <!-- Ville -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                       Ville
                                    </label>
                                    <input type="text"
                                           name="ville"
                                           placeholder="ville"
                                           value="{{ isset($user) ? $user->ville : '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"
                                           :class="errors.ville ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.ville">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>

                                </div>

                                <!-- Code Postal -->
                                <div>
                                    <label for="postal_code"
                                           class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Code Postal
                                    </label>
                                    <input id="postal_code"
                                           name="postal_code"
                                           type="text"
                                           placeholder="Code Postal"
                                           value="{{ isset($user) ? $user->postal_code : '' }}"
                                           class="p-2 border rounded w-full dark:bg-gray-700 dark:text-white"
                                           :class="errors.postal_code ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                    <template x-if="errors.postal_code">
                                        <p class="mt-1 text-sm camp">Ce champ est requis</p>
                                    </template>
                                </div>

                                <!-- Province -->
                                <div>
                                    <label for="province"
                                           class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Province
                                    </label>
                                    <select id="province"
                                            name="province"
                                            x-model="province"
                                            @change="mettreAJourRegion()"
                                            class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"
                                            :class="errors.province ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                        <option value="">Sélectionnez une province</option>
                                        <template x-for="(regions, prov) in provinces" :key="prov">
                                            <option :value="prov" x-text="prov" :selected="prov === province"></option>
                                        </template>
                                    </select>
                                    <template x-if="errors.province">
                                        <p class="mt-1 text-sm camp">Veuillez sélectionner une province</p>
                                    </template>
                                </div>

                                <!-- Région -->
                                <div x-show="regionOptions.length">
                                    <label for="region"
                                           class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                        Région
                                    </label>
                                    <select id="region"
                                            name="region"
                                            x-model="region"
                                            class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"
                                            :class="errors.region ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                        <option value="">Sélectionnez une région</option>
                                        <template x-for="r in regionOptions" :key="r">
                                            <option :value="r" x-text="r" :selected="r === region"></option>
                                        </template>
                                    </select>
                                    <template x-if="errors.region">
                                        <p class="mt-1 text-sm camp">Veuillez sélectionner une région</p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Comprobante de paiement -->
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                <strong>Note :</strong> Une fois votre commande validée, vous recevrez un e-mail de confirmation.
                                Versez ensuite le montant sur le compte <strong>BE38 3100 5385 3072</strong> en précisant dans la communication le numéro de commande repris dans le mail de confirmation.
                                <br>Dès réception de votre paiement, nous vous enverrons la commande à l’adresse clairement indiquée.
                                <br><br>Merci de joindre ci-dessous un <strong>justificatif de paiement</strong> pour le traitement de votre commande.
                            </p>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                Justificatif de paiement
                            </label>
                            <input type="file"
                                   name="proof"
                                   id="proof"
                                   accept="image/*,application/pdf"
                                   class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"
                                   :class="errors.proof ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                            {{--
                            <template x-if="errors.proof">
                                <p class="mt-1 text-sm camp">Ce champ est requis</p>
                            </template>
                            --}}
                        </div>
                    </div>

                    <!-- Colonne Droite : Produits -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md sticky top-4">
                            <h2 class="text-3xl font-semibold mb-4 dark:text-white sticky top-0 z-10 bg-white dark:bg-gray-800 py-2">
                                Vos Produits
                            </h2>
                            <div class="space-y-4">
                                @foreach ($cart as $product)
                                    <div class="flex justify-between items-center border-b pb-4 dark:border-gray-600">
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset($product['image']) }}"
                                                 alt="Produit"
                                                 class="w-24 h-auto rounded">
                                            <div>
                                                <h3 class="font-medium dark:text-white">
                                                    {{ $product['name'] }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Quantité : {{ $product['quantity'] }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($product['choix'] > 0)
                                            <div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">
                                                    {{ $product['choix'] }}
                                                </span>
                                            </div>
                                        @endif
                                        <p class="text-lg font-semibold dark:text-white">
                                            {{ number_format($product['price'], 2) }} €
                                        </p>
                                    </div>
                                @endforeach

                                <!-- Résumé -->
                                    <div class="mt-4 pt-4 border-t dark:border-gray-600">
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2">
                                            <span>Subtotal :</span>
                                            <span x-text="`${subtotal.toFixed(2)} €`"></span>
                                        </div>

                                        @if($user->isMember() && $user->getMemberDiscount() > 0)
                                            <div class="flex justify-between text-sm text-green-600 dark:text-green-400 mb-2">
                                                <span>Réduction membre (<span x-text="memberDiscountPercent.toFixed(2)"></span>%) :</span>
                                                <span>-<span x-text="discountAmount.toFixed(2)"></span> €</span>
                                            </div>
                                            <div class="flex justify-between text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                                <span>Total après réduction :</span>
                                                <span x-text="`${baseTotal.toFixed(2)} €`"></span>
                                            </div>
                                        @endif

                                        <template x-if="delivery === 'livraison'">
                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                <span>Frais de livraison :</span>
                                                <span x-text="`+${deliveryFee.toFixed(2)} €`"></span>
                                            </div>
                                        </template>

                                        <template x-if="delivery === 'retrait'">
                                            <div class="flex justify-between text-sm text-green-600 dark:text-green-400 mb-2">
                                                <span>Retrait en magasin :</span>
                                                <span>Gratuit</span>
                                            </div>
                                        </template>

                                        <div class="flex justify-between items-center text-xl font-bold pt-2 border-t dark:border-gray-600">
                                            <span class="dark:text-white">Total final :</span>
                                            <span class="dark:text-white" x-text="finalTotal.toFixed(2) + ' €'"></span>
                                        </div>
                                    </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 mt-5">
                                <button type="submit"
                                        :disabled="loading"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    Confirmer l'Achat
                                </button>

                                <button @click.prevent="annulerAchat"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="text-center py-10">
                <p class="text-xl text-gray-700 dark:text-white font-semibold mb-4">
                    Vous devez être connecté pour finaliser votre commande.
                </p>
                <a href="{{ route('login') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Se connecter
                </a>
            </div>
        @endauth
    @endif

    @push('scripts')
        <script>
            function checkout() {
                return {
                    cart: @js($cart),
                    notification: '',
                    notificationType: 'success',
                    delivery: 'retrait',
                    quantity: {{ collect($cart)->sum('quantity') }},

                    // 🔥 CALCULAR SUBTOTAL Y DESCUENTO
                    @php

                        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                        $memberDiscount = auth()->check() ? auth()->user()->getMemberDiscount() : 0;
                        $discountAmount = ($subtotal * $memberDiscount) / 100;
                        $baseTotal = $subtotal - $discountAmount;
                    @endphp

                    subtotal: {{ $subtotal }},
                    memberDiscountPercent: {{ $memberDiscount }},
                    discountAmount: {{ $discountAmount }},
                    baseTotal: {{ $baseTotal }},
                    loading: false,

                    get totalWeight() {
                        return this.cart.reduce((sum, item) => sum + (item.weight * item.quantity), 0);
                    },

                    get deliveryFee() {
                        const weight = this.totalWeight;
                        if (this.delivery === 'retrait') return 0;

                        if (weight > 0 && weight <= 100) return 3.00;
                        if (weight > 100 && weight <= 350) return 4.50;
                        if (weight > 350 && weight <= 1000) return 7.60;
                        if (weight > 1000 && weight <= 2000) return 7.90;
                        if (weight > 2000 && weight <= 10000) return 10.70;
                        if (weight > 10000 && weight <= 30000) return 18.60;

                        return 0;
                    },

                    get finalTotal() {
                        return this.baseTotal + this.deliveryFee;
                    },

                    region: @json(old('region', $user->region ?? '')),
                    province: @json(old('province', $user->province ?? '')),
                    regionOptions: [],
                    provinces: {
                        'Bruxelles-Capitale': ['Bruxelles'],
                        'Brabant wallon': ['Nivelles', 'Wavre'],
                        'Hainaut': ['Mons', 'Charleroi', 'Tournai', 'Soignies', 'Ath'],
                        'Liège': ['Liège', 'Verviers', 'Huy', 'Waremme'],
                        'Luxembourg': ['Arlon', 'Marche-en-Famenne', 'Neufchâteau', 'Bastogne'],
                        'Namur': ['Namur', 'Dinant', 'Philippeville'],
                        'Flandre orientale': ['Gand', 'Alost', 'Eeklo', 'Dendermonde', 'Saint-Nicolas'],
                        'Flandre occidentale': ['Bruges', 'Courtrai', 'Ypres', 'Furnes', 'Tielt'],
                        'Anvers': ['Anvers', 'Malines', 'Turnhout'],
                        'Limbourg': ['Hasselt', 'Tongres', 'Maaseik'],
                        'Brabant flamand': ['Louvain', 'Hal-Vilvorde']
                    },

                    mettreAJourRegion() {
                        this.regionOptions = this.provinces[this.province] || [];
                        if (!this.regionOptions.includes(this.region)) {
                            this.region = '';
                        }
                    },

                    init() {
                        this.mettreAJourRegion();
                        if (this.province && this.provinces[this.province]) {
                            this.regionOptions = this.provinces[this.province];

                            if (!this.regionOptions.includes(this.region)) {
                                this.region = this.regionOptions[0];
                            }
                        }
                    },

                    async confirmerAchat() {
                        if (this.loading) return;
                        this.loading = true;

                        try {
                            const first_name = document.querySelector('input[name="first_name"]').value.trim();
                            const second_name = document.querySelector('input[name="second_name"]').value.trim();
                            const email = document.querySelector('input[name="email"]').value.trim();
                            const telephone = document.querySelector('input[name="telephone"]').value.trim();
                            const society = document.querySelector('input[name="society"]').value.trim();
                            const address = document.getElementById('address')?.value.trim();
                            const ville = document.querySelector('input[name="ville"]')?.value.trim();
                            const postal_code = document.querySelector('input[name="postal_code"]')?.value.trim();

                            // Validación
                            this.errors.first_name = !first_name;
                            this.errors.second_name = !second_name;
                            this.errors.email = !email;
                            this.errors.telephone = !telephone;

                            if (this.delivery === 'livraison') {
                                this.errors.address = !address;
                                this.errors.ville = !ville;
                                this.errors.postal_code = !postal_code;
                                this.errors.region = !this.region;
                                this.errors.province = !this.province;
                            }

                            if (Object.values(this.errors).some(e => e)) {
                                this.showNotification("Veuillez remplir tous les champs requis.", 'error');
                                this.loading = false;
                                return;
                            }

                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(email)) {
                                this.showNotification("Veuillez entrer une adresse email valide.", 'error');
                                this.loading = false;
                                return;
                            }

                            // Crear FormData
                            const formData = new FormData();
                            formData.append('first_name', first_name);
                            formData.append('second_name', second_name);
                            formData.append('email', email);
                            formData.append('telephone', telephone);
                            formData.append('society', society);
                            formData.append('delivery', this.delivery);
                            formData.append('subtotal', this.subtotal.toFixed(2));
                            formData.append('member_discount_percent', this.memberDiscountPercent.toFixed(2));
                            formData.append('discount_amount', this.discountAmount.toFixed(2));
                            formData.append('base_total', this.baseTotal.toFixed(2));
                            formData.append('deliveryFee', this.deliveryFee.toFixed(2));
                            formData.append('total', this.finalTotal.toFixed(2));
                            formData.append('products', JSON.stringify(@json($cart)));

                            if (this.delivery === 'livraison') {
                                formData.append('address', address);
                                formData.append('ville', ville);
                                formData.append('postal_code', postal_code);
                                formData.append('province', this.province);
                                formData.append('region', this.region);
                            }

                            const response = await fetch("{{ route('order.store') }}", {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (!response.ok || data.error) {
                                this.showNotification(data.error || "Erreur inconnue.", 'error');
                                this.loading = false;
                                return;
                            }

                            this.showNotification("Commande enregistrée avec succès !");

                            localStorage.removeItem('cart');
                            localStorage.removeItem('cart-total');

                            setTimeout(() => {
                                window.location.href = `/facture/${data.order_id}`;
                            }, 2000);

                        } catch (err) {
                            console.error(err);
                            this.showNotification("Erreur lors de l'envoi du formulaire.", 'error');
                            this.loading = false;
                        }
                    },

                    annulerAchat() {
                        if (confirm("Voulez-vous vraiment annuler l'achat ? Les produits seront supprimés du panier.")) {
                            fetch("{{ route('cart.clear') }}", {
                                method: "DELETE",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                }
                            }).then(() => {
                                this.items = [];
                                this.total = 0;
                                localStorage.removeItem('cart');
                                localStorage.removeItem('cart-total');
                                window.location.href = "{{ route('boutique.index') }}";
                            });
                        }
                    },

                    showNotification(message, type = 'success') {
                        this.notification = message;
                        this.notificationType = type;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                this.notification = '';
                            }, 3000);
                        });
                    },

                    errors: {
                        first_name: false,
                        second_name: false,
                        email: false,
                        telephone: false,
                        address: false,
                        ville: false,
                        postal_code: false,
                        region: false,
                        province: false,
                    },
                };
            }
        </script>
    @endpush
</x-layout>

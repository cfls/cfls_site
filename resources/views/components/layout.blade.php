
@props(['logo' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{asset('favicon.ico')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{asset('favicon.svg')}}" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}" />
    <link rel="manifest" href="/site.webmanifest" />

    <meta name="google-site-verification" content="K2SNTPwGYEhcovT80QeRMqnj9ZJUn6NvzzY2haOq5M8" />
    <meta name="apple-mobile-web-app-title" content="cfls" />
    @if(app()->environment('production'))
    <meta name="robots" content="index,follow">
    @else
    <meta name="robots" content="noindex,nofollow">
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="Cfls | Centre Francophone de la Langue des Signes">
    <meta property="og:description" content="Le Centre Francophone de la Langue des Signes s'est donné pour mission de diffuser la langue des signes par des cours, des publications et de la recherche en L.S.">
    <meta name="keywords" content="langue des signes, cours LSF, CFLS, formation LSF, centre francophone, langue des signes Belgique">
    <meta property="og:image" content="{{ asset('img/og-cfls-1200x630.png') }}">
    <meta property="og:url" content="https://cfls.be">
    <meta property="og:site_name" content="C.F.L.S. asbl">
    <meta property="og:type" content="website">

    <title>{{ $title . ' - ' .  config('app.name', 'Laravel') ?? config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
{{--    <link href="https://fonts.bunny.net/css?family=alata:400,500&display=swap" rel="stylesheet" />--}}
    <link href="https://fonts.bunny.net/css?family=assistant:200,300,400,500,600,700,800" rel="stylesheet" />

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ccc950231e.js" crossorigin="anonymous"></script>
    <script>
        const html = document.documentElement;
        const theme = localStorage.getItem('color-theme');

        if (theme === 'dark') {
            html.classList.add('dark');
            html.classList.remove('light');
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
        }

        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: [],

                // Getter dinámico que suma todas las cantidades
                get count() {
                    return this.items.reduce((total, item) => total + (item.quantity || 1), 0);
                }
            });

            // Inicializar desde localStorage
            const storedItems = JSON.parse(localStorage.getItem('cart')) || [];
            Alpine.store('cart').items = storedItems;
        });

        // Escuchar cambios del carrito
        window.addEventListener('cart-updated', () => {
            const updated = JSON.parse(localStorage.getItem('cart')) || [];
            Alpine.store('cart').items = updated;
        });
    </script>
    <script src="{{asset('/js/cookies.js')}}" defer></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('css')
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-52KWCPNS4C"></script>
    <script>
        function enableGoogleAnalytics() {
            // Cargar el script de GA dinámicamente
            let gtagScript = document.createElement('script');
            gtagScript.src = "https://www.googletagmanager.com/gtag/js?id=G-52KWCPNS4C";
            gtagScript.async = true;
            document.head.appendChild(gtagScript);

            gtagScript.onload = () => {
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'G-52KWCPNS4C');
            };
        }

        // Mostrar GA solo si el usuario ya aceptó cookies
        document.addEventListener("DOMContentLoaded", () => {
            if (getCookie("cookie_consent") === "accepted") {
                enableGoogleAnalytics();
            }
        });
    </script>
    <!-- End Google Analytics -->
    @fluxAppearance
</head>

<body
    x-data="{
        open: false,
        isOpen: false,
        ...cart()
    }"
    :class="{ 'overflow-hidden': open || isOpen }"
    @add-to-cart.window="addToCart($event.detail.id, $event.detail.quantity, $event.detail.choix)"
    class="sm:overflow-auto dark:bg-slate-900"
>

     @include('layouts.includes.navegation', ['logo' => $logo])
     @include('layouts.includes.sidebar')
     @include('components.slide-over')





    <div
        class="border-gray-200 border-dashed rounded-lg dark:border-gray-70">
        {{ $slot }}
    </div>


    <div x-cloak x-on:click="open = false" x-show="open"
        class="bg-gray-900 bg-opacity-50 fixed inset-0 z-30 sm:hidden"></div>


       @include('layouts.includes.footer', ['logo' => $logo ])


       @stack('modals')

        @stack('scripts')

        @livewireScripts
         @fluxScripts

     <x-top/>
     <!-- Banner de preferencias de accesibilidad -->
     <div id="accessibility-banner" style="display: none;"
          class="fixed bottom-4 right-4 max-w-sm p-4 bg-white border border-gray-300 rounded-lg shadow-lg z-50 dark:bg-gray-800 dark:border-gray-700">

         <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
             Gérer vos préférences de cookies
         </h2>

         <p class="text-sm mb-4 text-gray-800 dark:text-gray-100">
             Nous utilisons des cookies pour comprendre comment vous interagissez avec notre site.
             En acceptant, vous consentez à notre utilisation de ces cookies.
             <a href="{{route('general-4')}}" target="_self"
                class="text-blue-600 hover:underline dark:text-blue-400">
                 Politique de confidentialité
             </a>.
         </p>

         <!-- Botones -->
         <div class="flex justify-end gap-2">
             <button onclick="savePreferences('rejected')"
                     class="px-3 py-1 border rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-sm">
                 Refuser
             </button>
             <button onclick="savePreferences('accepted')"
                     class="px-3 py-1 border rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">
                 Accepter
             </button>
         </div>
     </div>

     @push('scripts')
         <script>
             document.addEventListener('alpine:init', () => {
                 Alpine.store('cart', {
                     items: JSON.parse(localStorage.getItem('cart')) || [],
                     get count() {
                         return this.items.reduce((sum, item) => sum + item.quantity, 0);
                     },
                     add(product) {
                         const existing = this.items.find(p => p.id === product.id);
                         if (existing) {
                             existing.quantity += product.quantity;
                         } else {
                             this.items.push(product);
                         }
                         this.save();
                     },
                     save() {
                         localStorage.setItem('cart', JSON.stringify(this.items));
                         window.dispatchEvent(new CustomEvent('cart-updated'));
                     }
                 });
             });
         </script>

         <script>
             function cart() {
                 return {
                     items: [],
                     total: 0,
                     isOpen: false,
                     selectedProductId: null,
                     productDetails: null,
                     notification: '',

                     init() {
                         const saved = localStorage.getItem('cart');
                         const total = localStorage.getItem('cart-total');
                         if (saved) this.items = JSON.parse(saved);
                         if (total) this.total = parseFloat(total);
                         this.items.forEach(i => i.totalPrice = i.price * i.quantity);

                         window.addEventListener('open-slide-over', async (e) => {

                             this.isOpen = true;
                             this.selectedProductId = e.detail?.id ?? null;
                             if (this.selectedProductId) await this.loadProductDetails(this.selectedProductId);
                         });
                     },

                     async loadProductDetails(id) {
                         const res = await fetch(`/api/product/${id}`);
                         this.productDetails = await res.json();
                     },

                     async addToCart(productId, quantity = 1) {
                         const existing = this.items.find(i => i.id === productId);
                         if (existing) {
                             existing.quantity += quantity;
                         } else {
                             const res = await fetch(`/api/product/${productId}`);
                             const data = await res.json();

                             this.items.push({
                                 id: data.id,
                                 name: data.name,
                                 slug: data.slug,
                                 price: parseFloat(data.price),
                                 image: data.images?.[0]?.image_path ? `/storage/${data.images[0].image_path}` : '/img/default.jpg',
                                 quantity,
                                // totalPrice: data.price * quantity,
                             });
                         }
                         this.updateTotal();
                         this.isOpen = true;
                         this.showNotification('Produit ajouté au panier');
                     },

                     updateTotal() {
                         this.total = this.items.reduce((sum, i) => sum + i.price * i.quantity, 0);
                         this.items.forEach(i => i.totalPrice = i.price * i.quantity);
                         localStorage.setItem('cart', JSON.stringify(this.items));
                         localStorage.setItem('cart-total', this.total.toFixed(2));
                         window.dispatchEvent(new CustomEvent('cart-updated'));
                     },

                     showNotification(msg) {
                         this.notification = msg;
                         setTimeout(() => this.notification = '', 3000);
                     },

                     clearCart() {
                         this.items = [];
                         this.total = 0;
                         localStorage.removeItem('cart');
                         localStorage.removeItem('cart-total');
                         window.dispatchEvent(new CustomEvent('cart-updated'));
                         setTimeout(() => this.isOpen = false, 3000);
                     },
                 };
             }
         </script>
         <script>
             // On page load or when changing themes, best to add inline in `head` to avoid FOUC
             if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                 document.documentElement.classList.add('dark')
             } else {
                 document.documentElement.classList.remove('dark')
             }
         </script>

     @endpush



</body>

</html>

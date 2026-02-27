@props(['data'])

<section class="max-w-screen-2xl mx-auto dark:bg-gray-900 p-6 shadow-lg rounded-lg">
    <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-bold mb-8 text-center dark:text-white">
        Contactez-nous
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center dark:text-white max-w-4xl mx-auto">

        <!-- Adresse -->
        <div class="mb-4 text-center">
            <h3 class="md:text-2xl font-semibold dark:text-white">Adresse</h3>
            <p>{{ $data->address }}</p>
            <p>{{ $data->zip }} {{ $data->city }} ({{ $data->state }})</p>
        </div>

        <!-- Transport public -->
        <div class="mb-4 text-center">
            <h3 class="md:text-2xl font-semibold dark:text-white">Transports publics</h3>
            {!! $data->transport !!}
        </div>

        <!-- Informations de contact -->
        <div class="mb-4 text-center">
            <h3 class="md:text-2xl font-semibold dark:text-white">Contact</h3>
            <p>
                Email :
                <a href="mailto:info@cfls.be" class="text-blue-500">
                    {{ $data->email }}
                </a>
            </p>
            <p>
                Téléphone:
                <a href="tel:+3224781448" class="text-blue-500">
                    {{ $data->phone }}
                </a>
            </p>
            <p>
                Whatsapp:
                <a href="https://wa.me/32483841011" target="_blank" class="text-green-500 font-bold">
                    {{ $data->mobile }}
                </a>
            </p>
        </div>

        <!-- Horaires d'ouverture -->
        <div class="mb-4 text-center">
            <h3 class="md:text-2xl font-semibold dark:text-white">Horaires d'ouverture</h3>
            <p>{{ $data->scheduler }}</p>
        </div>

        <!-- Nouvelle section Informations légales -->
        <div class="mb-4 text-center md:col-span-2 border-t pt-6">
            <h3 class="md:text-2xl font-semibold dark:text-white mb-4">
                Informations légales et bancaires
            </h3>
            <div class="text-gray-700 dark:text-gray-300 space-y-2">
                <p><strong>RPM :</strong> Bruxelles</p>
                <p><strong>N° d'entreprise :</strong> BE 0422.073.229</p>
                <p><strong>N° de compte :</strong> BE38 3100 5385 3072</p>
                <p><strong>BIC :</strong> BBRUBEBB</p>
            </div>
        </div>

    </div>

    <!-- Carte Google -->
    <div class="mt-6 flex justify-center">
        <iframe src="{{ $data->googlemap }}" class="w-full h-64 rounded-lg" loading="lazy"></iframe>
    </div>
</section>
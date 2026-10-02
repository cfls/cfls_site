<section class="bg-white dark:bg-gray-900 mb-4">
    <div class="max-w-screen-2xl mx-auto px-4 py-12">

        {{-- Announcement --}}
        <div class="flex flex-col items-center justify-center gap-3 rounded-xl border border-yellow-300 bg-yellow-50 px-6 py-6 text-center dark:border-yellow-500/40 dark:bg-yellow-500/10">
            <div class="text-yellow-500 dark:text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <p class="text-lg font-bold text-yellow-800 dark:text-yellow-300">Avis / Notice</p>
            <p class="text-base font-bold text-yellow-700 dark:text-yellow-400">
                Le CFLS est fermé aujourd'hui, le {{ \Carbon\Carbon::today()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}.
            </p>
            <p class="text-sm font-semibold text-yellow-600 dark:text-yellow-500">
                Réouverture lundi {{ \Carbon\Carbon::parse('next monday')->locale('fr')->isoFormat('D MMMM YYYY') }}.
            </p>
        </div>

    </div>
</section>

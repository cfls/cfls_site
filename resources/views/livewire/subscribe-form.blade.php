<div class="w-full max-w-xl">
    <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3">
        <label for="email-address" class="sr-only">Adresse e-mail</label>

        <input
                id="email-address"
                name="email"
                type="email"
                wire:model.defer="email"
                autocomplete="email"
                required
                placeholder="Saisissez votre e-mail"
                class="flex-1 rounded-md bg-white dark:bg-gray-900 px-4 py-3 text-base text-gray-900 dark:text-white outline outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-600 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-red-500"
        >

        <button
                type="submit"
                class="rounded-md bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
        >
            S’inscrire
        </button>
    </form>

    @error('email')
    <div
            wire:key="email-error-{{ md5($message) }}-{{ now()->timestamp }}"
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="mt-2 text-sm text-white bg-red-500 dark:bg-red-900/30 p-2 rounded-lg"
    >
        {{ $message }}
    </div>
    @enderror

    @if ($successMessage)
        <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                class="mt-3 rounded-lg bg-green-100 dark:bg-green-900/30 p-4 text-sm text-green-700 dark:text-green-300"
        >
            {{ $successMessage }}
        </div>
    @endif
</div>
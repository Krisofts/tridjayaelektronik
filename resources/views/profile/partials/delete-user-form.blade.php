<section class="rounded-2xl border border-red-200 bg-white p-6 lg:p-8 dark:border-red-900/30 dark:bg-gray-900">

    <!-- HEADER -->
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-red-600 dark:text-red-400">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Once your account is deleted, all data will be permanently removed. Please download any important data before proceeding.') }}
        </p>
    </header>

    <!-- TRIGGER BUTTON -->
    <x-danger-button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-lg px-5 py-2"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    <!-- MODAL -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>

        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">

            @csrf
            @method('delete')

            <!-- TITLE -->
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ __('This action is permanent. Please enter your password to confirm deletion.') }}
            </p>

            <!-- PASSWORD -->
            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-800 dark:border-gray-700"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <!-- ACTIONS -->
            <div class="mt-6 flex justify-end gap-3">

                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="rounded-lg px-4 py-2"
                >
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="rounded-lg px-5 py-2">
                    {{ __('Delete Account') }}
                </x-danger-button>

            </div>

        </form>

    </x-modal>

</section>
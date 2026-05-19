<section class="rounded-2xl border border-gray-200 bg-white p-6 lg:p-8 dark:border-gray-800 dark:bg-gray-900">

    <!-- HEADER -->
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Use a strong and random password to keep your account secure.') }}
        </p>
    </header>

    <!-- FORM -->
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- CURRENT PASSWORD -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- NEW PASSWORD -->
        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- CONFIRM PASSWORD -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- ACTION -->
        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="rounded-lg px-5 py-2">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-500 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>

    </form>

</section>
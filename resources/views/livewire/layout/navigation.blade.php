<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();
    $this->redirect('/login', navigate: true);
};

?>

<nav x-data="{ open: false }" class="bg-primary-700 dark:bg-gray-800 border-b border-primary-800 dark:border-gray-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <img src="{{ asset('logo/lg_karitas.png') }}" alt="Logo" class="h-12 w-auto">
                    </a>
                </div>
                <!-- Menu Desktop -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Kamar Inap') }}
                    </x-nav-link>
                    <x-nav-link :href="route('monitoring.icu.history.list')" :active="request()->routeIs('monitoring.icu.history.list')" wire:navigate>
                        {{ __('Riwayat ICU') }}
                    </x-nav-link>
                    <x-nav-link :href="route('patient.picu.history.list')" :active="request()->routeIs('patient.picu.history.list')" wire:navigate>
                        {{ __('Riwayat PICU') }}
                    </x-nav-link>
                    <x-nav-link :href="route('patient.nicu.history')" :active="request()->routeIs('patient.nicu.history')" wire:navigate>
                        {{ __('Riwayat NICU') }}
                    </x-nav-link>
                    <x-nav-link :href="route('patient.anestesi.history')" :active="request()->routeIs('patient.anestesi.history')" wire:navigate>
                        {{ __('Riwayat Intra Anestesi') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Tombol Dark Mode selalu muncul -->
                <button @click="window.theme.toggle()" x-bind:title="window.theme.darkMode ? 'Switch to light' : 'Switch to dark'" class="p-2 rounded-full text-primary-200 dark:text-gray-500 hover:text-white dark:hover:text-gray-400 hover:bg-primary-600 dark:hover:bg-gray-700 focus:outline-none">
                    <svg x-show="!window.theme.darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="window.theme.darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-15.66l-.707.707M4.04 19.96l-.707.707M21 12h-1M4 12H3m15.66 8.66l-.707-.707M4.04 4.04l-.707-.707M12 18a6 6 0 100-12 6 6 0 000 12z"></path>
                    </svg>
                </button>

                <!-- User Dropdown Desktop -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md
                                           text-primary-100 dark:text-gray-400
                                           hover:text-white dark:hover:text-gray-200
                                           focus:outline-none transition ease-in-out duration-150">
                                @php
                                $displayName = auth()->user()->is_super_admin
                                ? 'Admin Utama'
                                : (auth()->user()->pegawai?->nama ?? auth()->user()->id_user);
                                @endphp

                                <div x-data="{{ json_encode(['name' => $displayName]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger Mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md
                                   text-primary-200 dark:text-gray-500
                                   hover:text-white dark:hover:text-gray-400
                                   hover:bg-primary-600 dark:hover:bg-gray-700
                                   focus:outline-none focus:bg-primary-600 dark:focus:bg-gray-700
                                   focus:text-white dark:focus:text-gray-400
                                   transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Kamar Inap') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('monitoring.icu.history.list')" :active="request()->routeIs('monitoring.icu.history.list')" wire:navigate>
                {{ __('Riwayat ICU') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patient.picu.history.list')" :active="request()->routeIs('patient.picu.history.list')" wire:navigate>
                {{ __('Riwayat PICU') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patient.anestesi.history')" :active="request()->routeIs('patient.anestesi.history')" wire:navigate>
                {{ __('Riwayat Anestesi') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => $displayName]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? 'Email tidak tersedia' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>

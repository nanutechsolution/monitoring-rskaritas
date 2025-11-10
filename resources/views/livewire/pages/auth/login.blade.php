<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();
    $this->form->authenticate();
    Session::regenerate();
    $this->redirectIntended(default: route('dashboard'), navigate: true);
};

?>

<!-- Form -->
<form wire:submit.prevent="login" class="space-y-5">

    <!-- ID User -->
    <div>
        <x-input-label for="id_user" :value="__('ID Khanza')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
        <x-text-input wire:model="form.id_user" id="id_user" type="text" name="id_user" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 focus:border-karitas-blue-500 focus:ring focus:ring-karitas-blue-200 dark:focus:ring-karitas-blue-800 transition" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('form.id_user')" class="mt-2" />
    </div>

    <!-- Password -->
    <div>
        <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
        <x-text-input wire:model="form.password" id="password" type="password" name="password" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 focus:border-karitas-blue-500 focus:ring focus:ring-karitas-blue-200 dark:focus:ring-karitas-blue-800 transition" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
        <label for="remember" class="inline-flex items-center space-x-2">
            <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-karitas-blue-600 focus:ring-karitas-blue-500">
            <span class="text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
        </label>
    </div>

    <!-- Tombol Login -->
    <div>
        <x-primary-button class="w-full justify-center bg-karitas-blue-600 hover:bg-karitas-blue-700 text-white rounded-xl py-2.5 shadow-md transition ease-in-out duration-200">
            {{ __('Masuk') }}
        </x-primary-button>
    </div>
</form>

<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    /**
     * PERUBAHAN 1:
     * - Properti diubah dari $email menjadi $id_user.
     * - Validasi 'email' dihilangkan, karena username bukan email.
     */
    #[Validate('required|string')]
    public string $id_user = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        /**
         * - Menggunakan 'id_user' untuk proses autentikasi.
         * - Menampilkan pesan error pada field 'form.id_user'.
         */
        if (!Auth::attempt(['id_user' => $this->id_user, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.id_user' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        /**
         * PERUBAHAN 3:
         * - Menampilkan pesan error throttle pada field 'form.id_user'.
         */
        throw ValidationException::withMessages([
            'form.id_user' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        /**
         * - Menggunakan $this->id_user untuk membuat throttle key.
         */
        return Str::transliterate(Str::lower($this->id_user) . '|' . request()->ip());
    }
}

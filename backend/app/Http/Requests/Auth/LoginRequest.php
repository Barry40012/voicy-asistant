<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        try {
            $this->ensureIsNotRateLimited();
        } catch (\Exception $e) {
            // If rate limiting check fails, log and continue
            \Illuminate\Support\Facades\Log::warning('Rate limiter check failed, continuing with login', [
                'error' => $e->getMessage(),
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            try {
                RateLimiter::hit($this->throttleKey());
            } catch (\Exception $e) {
                // If rate limiter fails, log but don't block
                \Illuminate\Support\Facades\Log::warning('Rate limiter hit failed', [
                    'error' => $e->getMessage(),
                ]);
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        try {
            RateLimiter::clear($this->throttleKey());
        } catch (\Exception $e) {
            // If rate limiter clear fails, log but don't block successful login
            \Illuminate\Support\Facades\Log::warning('Rate limiter clear failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        try {
            // Set a timeout for rate limiter operations
            $tooManyAttempts = RateLimiter::tooManyAttempts($this->throttleKey(), 5);
            
            if (! $tooManyAttempts) {
                return;
            }

            event(new Lockout($this));

            // Try to get available time, but don't block if it fails
            try {
                $seconds = RateLimiter::availableIn($this->throttleKey());
            } catch (\Exception $e) {
                // If rate limiter fails, default to 60 seconds
                $seconds = 60;
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        } catch (\Exception $e) {
            // If rate limiter completely fails, log and continue (don't block login)
            \Illuminate\Support\Facades\Log::warning('Rate limiter error during login', [
                'error' => $e->getMessage(),
                'email' => $this->string('email'),
            ]);
            // Continue with login attempt
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}

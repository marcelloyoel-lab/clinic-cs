<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function store(LoginRequest $request)
  {
    // dd($request->all());
    $this->ensureIsNotRateLimited($request);
    $login = $request->login;

    $field = filter_var($login, FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'username';

    $credentials = [
        $field => $login,
        'password' => $request->password,
    ];

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
      $this->incrementLoginAttempts($request);
      // dd('failed');
        return back()
            ->withErrors([
                'login' => 'Invalid credentials.',
            ])
            ->onlyInput('login');
    }

    $this->clearLoginAttempts($request);
    $request->session()->regenerate();

    return redirect()->intended('/dashboard');
  }

  protected function ensureIsNotRateLimited(LoginRequest $request): void
  {
    $key = Str::transliterate(
      Str::lower($request->login).'|'.$request->ip()
      );
      
      if (! RateLimiter::tooManyAttempts($key, 5)) {
          return;
      }

      $seconds = RateLimiter::availableIn($key);

      throw ValidationException::withMessages([
          'login' => "Too many login attempts. Try again in {$seconds} seconds.",
      ]);
  }

  protected function incrementLoginAttempts(LoginRequest $request): void
  {
      $key = Str::transliterate(
          Str::lower($request->login).'|'.$request->ip()
      );

      RateLimiter::hit($key, 60);
  }

  protected function clearLoginAttempts(LoginRequest $request): void
  {
      $key = Str::transliterate(
          Str::lower($request->login).'|'.$request->ip()
      );

      RateLimiter::clear($key);
  }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

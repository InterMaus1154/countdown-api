<?php

namespace App\Http\Controllers;

use App\Data\LoginAttemptData;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth)
    {
    }

    public function loginView(): View|RedirectResponse
    {
        if ($this->auth->isLoggedIn()) {
            return to_route('timers.index');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $response = $this->auth->attempt(new LoginAttemptData(username: $request->input('username'), password: $request->input('password')));

        if(! $response->success || is_null($response->user)) {
            return redirect()->back()->withErrors(['invalid' => $response->message]);
        }

        return to_route('timers.index');
    }

    public function logout(): RedirectResponse
    {
        $this->auth->logout();
        return redirect()->back();
    }
}

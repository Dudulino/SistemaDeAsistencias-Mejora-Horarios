<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Colaboradores;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RateLimiter;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $remember = $request->has('remember');

        if(!Auth::attempt($request->only('email', 'password'), $remember)){
            return back()->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }
        $request->authenticate();

        $request->session()->regenerate();


        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        session(['api_token' => $token]);
        // return response()->json(['token' => $token, 'user' => $user], 200);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function login(Request $request)
    {

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'AccessToken' => $token,
            'TokenType' => 'Bearer',
            'user' => $user
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        // return $user->tokens()->first()->token; //Obtener token

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($user) {
            $user->tokens()->delete();
        }

        return redirect('/');
    }
}

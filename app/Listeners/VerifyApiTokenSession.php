<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class VerifyApiTokenSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle(Authenticated $event)
    {
        // verificar que existe la sesion "api_token" mientras el usuario este iniciado en sesion
        if (!session('api_token')) {
            //Si no existe regenerar la sesion con el token
            $user = Auth::user();
            // $user->destroyTokens
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            session(['api_token' => $token]);
        }
        //Si existe no hacer nada

    }
}

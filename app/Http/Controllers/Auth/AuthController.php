<?php

namespace App\Http\Controllers\Auth;

use Aacotroneo\Saml2\Saml2Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        // Antes de llegar aquí, la petición pasa por el middleware
        // verify.auth, que hace la autenticación (real o simulada).
        return redirect('/');
    }

    public function logout()
    {
        if (config('saml.simulator.enabled')) {
            Auth::logout();
            Session::forget('saml');
            Session::save();

            return redirect('/');
        }

        $idp = config('saml.idp');
        $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig($idp));

        Auth::logout();
        Session::forget('saml');

        return $saml2Auth->logout('/');
    }
}

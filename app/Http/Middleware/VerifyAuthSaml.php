<?php

namespace App\Http\Middleware;

use Aacotroneo\Saml2\Saml2Auth;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class VerifyAuthSaml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        }

        if (Auth::guest()) {
            if (! Session::has('saml')) {
                if (config('saml.simulator.enabled')) {
                    Session::put('saml', [
                        'attributes' => [
                            'uCorreo' => [config('saml.simulator.email')],
                            'uNombre' => [config('saml.simulator.name')],
                            'sn' => [config('saml.simulator.lastname')],
                            'givenName' => [config('saml.simulator.firstname')],
                            'displayName' => [config('saml.simulator.name')],
                        ],
                    ]);

                    return redirect(URL::full());
                }

                $idp = config('saml.idp');
                $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig($idp));

                return $saml2Auth->login(URL::full());
            }

            $userData = Session::get('saml');
            $email = $userData['attributes']['uCorreo'][0];
            $name = $userData['attributes']['uNombre'][0] ?? $userData['attributes']['displayName'][0];

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Str::password(32),
                ]
            );

            Auth::login($user);
        }

        return $next($request);
    }
}

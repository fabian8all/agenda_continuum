<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Simulador de SAML
    |---------------------------------------------------------------------------
    |
    | Mientras la universidad no entregue los metadatos definitivos del IdP
    | (ver docs/requirements.md, sección de suposiciones), el login federado
    | puede simularse con estos valores en vez de contactar a un IdP real.
    | Se lee vía config() en vez de env() directo para no romperse bajo
    | `config:cache` (ver App\Http\Middleware\VerifyAuthSaml).
    |
    */

    'simulator' => [
        'enabled' => (bool) env('SAML_SIMULATOR', false),
        'email' => env('SAML_SIMULATOR_EMAIL', 'docente@ucol.mx'),
        'name' => env('SAML_SIMULATOR_NAME', 'Docente de Prueba'),
        'firstname' => env('SAML_SIMULATOR_FIRSTNAME', 'Docente'),
        'lastname' => env('SAML_SIMULATOR_LASTNAME', 'de Prueba'),
    ],

    /*
    |---------------------------------------------------------------------------
    | IdP a usar para el login real
    |---------------------------------------------------------------------------
    |
    | Debe coincidir con una de las entradas de `idpNames` en
    | config/saml2_settings.php.
    |
    */

    'idp' => 'test',

];

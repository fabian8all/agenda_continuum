<?php

// Mismo IdP de prueba de DGRE ya usado en el proyecto hermano `redi`
// (../redi/redi-app/config/saml2/test_idp_settings.php). El entityId del SP
// de esta app es distinto y debe registrarse aparte con el administrador
// del IdP antes de que este flujo funcione de verdad — ver el handoff de
// esta tarea.
$this_idp_env_id = 'TEST';
$idp_host = env('SAML2_'.$this_idp_env_id.'_IDP_HOST', 'https://dgre2.ucol.mx/simplesaml');

return $settings = array(

    'strict' => true,

    'debug' => env('APP_DEBUG', false),

    'sp' => array(
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        'x509cert' => env('SAML2_'.$this_idp_env_id.'_SP_x509', ''),
        'privateKey' => env('SAML2_'.$this_idp_env_id.'_SP_PRIVATEKEY', ''),

        // Identificador (URI) de esta app ante el IdP. Debe registrarse con
        // el administrador del IdP de DGRE.
        'entityId' => env('SAML2_'.$this_idp_env_id.'_SP_ENTITYID', ''),

        'assertionConsumerService' => array(
            'url' => '',
        ),
        'singleLogoutService' => array(
            'url' => '',
        ),
    ),

    'idp' => array(
        'entityId' => env('SAML2_'.$this_idp_env_id.'_IDP_ENTITYID', $idp_host.'/saml2/idp/metadata.php'),
        'singleSignOnService' => array(
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL', $idp_host.'/saml2/idp/SSOService.php'),
        ),
        'singleLogoutService' => array(
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SL_URL', $idp_host.'/saml2/idp/SingleLogoutService.php'),
        ),
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_IDP_x509', ''),
    ),

    'security' => array(
        'nameIdEncrypted' => false,
        'authnRequestsSigned' => false,
        'logoutRequestSigned' => false,
        'logoutResponseSigned' => false,
        'signMetadata' => false,
        'wantMessagesSigned' => false,
        'wantAssertionsSigned' => false,
        'wantNameIdEncrypted' => false,
        'requestedAuthnContext' => true,
    ),

    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'DGRE',
            'emailAddress' => 'produccion_dgre@ucol.mx',
        ),
        'support' => array(
            'givenName' => 'DGRE',
            'emailAddress' => 'produccion_dgre@ucol.mx',
        ),
    ),

    'organization' => array(
        'en-US' => array(
            'name' => 'Agenda de Escenarios',
            'displayname' => 'Agenda de Escenarios',
            'url' => env('APP_URL', 'http://localhost'),
        ),
    ),

);

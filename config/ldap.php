<?php

$ldapSsl = (bool) env('LDAP_SSL', true);
$ldapTls = (bool) env('LDAP_TLS', false);

if ($ldapSsl === $ldapTls) {
    throw new LogicException('Enable exactly one encrypted LDAP transport: LDAPS or StartTLS.');
}

$ldapOptions = [
    LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_DEMAND,
];

if ($ldapCaCert = env('LDAP_CA_CERT')) {
    $ldapOptions[LDAP_OPT_X_TLS_CACERTFILE] = $ldapCaCert;
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default LDAP Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the LDAP connections below you wish
    | to use as your default connection for all LDAP operations. Of
    | course you may add as many connections you'd like below.
    |
    */

    'default' => env('LDAP_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | LDAP Connections
    |--------------------------------------------------------------------------
    |
    | Below you may configure each LDAP connection your application requires
    | access to. Be sure to include a valid base DN - otherwise you may
    | not receive any results when performing LDAP search operations.
    |
    */

    'connections' => [
        'default' => [
            'hosts' => [env('LDAP_HOSTS', 'pdc.yarsi.ac.id')],
            'username' => env('LDAP_USERNAME', null),
            'password' => env('LDAP_PASSWORD', null),
            'port' => env('LDAP_PORT', $ldapSsl ? 636 : 389),
            'base_dn' => env('LDAP_BASE_DN', 'dc=yarsi,dc=ac,dc=id'),
            'timeout' => env('LDAP_TIMEOUT', 5),
            'use_ssl' => $ldapSsl,
            'use_tls' => $ldapTls,
            'options' => $ldapOptions,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Logging
    |--------------------------------------------------------------------------
    |
    | When LDAP logging is enabled, all LDAP search and authentication
    | operations are logged using the default application logging
    | driver. This can assist in debugging issues and more.
    |
    */

    'logging' => [
        'enabled' => false,
        'channel' => env('LOG_CHANNEL', 'stack'),
        'level' => env('LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Cache
    |--------------------------------------------------------------------------
    |
    | LDAP caching enables the ability of caching search results using the
    | query builder. This is great for running expensive operations that
    | may take many seconds to complete, such as a pagination request.
    |
    */

    'cache' => [
        'enabled' => env('LDAP_CACHE', false),
        'driver' => env('CACHE_DRIVER', 'file'),
    ],

];

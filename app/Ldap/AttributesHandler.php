<?php

namespace App\Ldap;

use App\Models\User as DatabaseUser;
use LdapRecord\Models\OpenLDAP\User as LdapUser;
use Illuminate\Validation\ValidationException;

class AttributesHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        $role = $ldap->getFirstAttribute('title');
        if ($role != 'M') {
            throw ValidationException::withMessages([
                'username' => 'Anda tidak memiliki hak akses untuk login.',
            ]);
        }
        $database->username = $ldap->getFirstAttribute('uid') ?? $ldap->getFirstAttribute('cn');
        $database->name     = $ldap->getFirstAttribute('displayName');
        $database->nidn     = $ldap->getFirstAttribute('description');
        $database->role     = 'Dosen';
        $database->password = null;
    }
}

<?php
namespace App\Handlers;

use App\User as EloquentUser;
use Adldap\Models\User as LdapUser;

class LdapAttributeHandler
{
    /**
     * Synchronizes ldap attributes to the specified model.
     *
     * @param LdapUser     $ldapUser
     * @param EloquentUser $eloquentUser
     *
     * @return void
     */
    public function handle(LdapUser $ldapUser, EloquentUser $eloquentUser)
    {
        //dd($ldapuser); 
        $eloquentUser->name = $ldapUser->getFirstName() . " " . $ldapUser->getLastName();
        
        $temp = $ldapUser->dn;

        if((strpos(strtolower($temp),"staff") !== false)) $eloquentUser->is_staff = true;
        if((strpos(strtolower($temp),"student") !== false)) $eloquentUser->is_student = true;
        if((strpos(strtolower($temp),"faculty") !== false)) $eloquentUser->is_faculty = true;

        $eloquentUser->username = $ldapUser->getUserPrincipalName();
        $eloquentUser->email = $ldapUser->getUserPrincipalName();
       
    }
}
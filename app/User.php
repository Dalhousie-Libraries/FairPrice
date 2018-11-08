<?php

namespace App;

use Adldap\Laravel\Traits\AdldapUserModelTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','memberof', 'username', 'is_student', 'faculty_id', 'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    function getIsAdminAttribute() {
        return $this->role==3;
    }

    function getIsResourceTeamAttribute() {
        return $this->role==2;
    }

    function getIsLibrarianAttribute() {
        return $this->role==1;
    }

    function getTypeAttribute() {
        if ($this->is_student) {
            return 1;
        }
        if ($this->is_faculty) {
            return 3;
        }
        if ($this->is_staff) {
            return 2;
        }
        return 0;
    }

    function getTypeStringAttribute() {
        if ($this->is_student) {
            return "Student";
        }
        if ($this->is_faculty) {
            return "Faculty";
        }
        if ($this->is_staff) {
            return "Staff";
        }
        return "Other";
    }

    function getRoleStringAttribute() {
        if ($this->getIsLibrarianAttribute()) {
            return "Librarian";
        }
        if ($this->getIsResourceTeamAttribute()) {
            return "Resource Team Member";
        }
        if ($this->getIsAdminAttribute()) {
            return "Admin";
        }
        return "";
    }
    protected $appends = ['isStaff', 'isFaculty', 'isStudent', 'isAdmin', 'isLibrarian', 'type', 'typestring', 'rolestring'];
    
}

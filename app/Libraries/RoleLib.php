<?php

namespace App\Libraries;

class RoleLib{

	/**
	 * Ez az azonosítójú felhasználó nem törölhető
	 */
	public $systemAdminId = 1;
	
	/**
	 * Elérhető jogosultságok.
	 */
    public $roles = [
		'admin' => [
			'role' => 'admin',
			'lang' => 'Site.siteRoleAdmin'
		],
		'department_admin'=> [
			'role' => 'department_admin',
			'lang' => 'Site.siteRoleDepartmentAdmin'
		],
		'user'=> [
			'role' => 'user',
			'lang' => 'Site.siteRoleUser'
		]
	];

    public function getRolesString() : string{
        return implode(',', array_keys($this->roles));
    }
}
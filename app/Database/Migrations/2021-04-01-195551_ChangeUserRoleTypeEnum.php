<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeUserRoleTypeEnum extends Migration
{
	public function up()
	{
		$fields = [
			'role' => [
					'type' => 'ENUM',
					'constraint' => ['admin', 'department_admin', 'user'],
					'default' => 'user',
					'null' => false
			],
		];
		$this->forge->modifyColumn('users', $fields);
	}

	public function down()
	{
		//
	}
}

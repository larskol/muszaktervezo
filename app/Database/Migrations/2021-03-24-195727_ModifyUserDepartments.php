<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUserDepartments extends Migration
{
	public function up()
	{
		$fields = [
			'departments' => [
				'type' => 'json',
				'null' => true
			]
		];
		$this->forge->addColumn('users', $fields);
		$this->forge->dropTable('department_admins');
	}

	public function down()
	{
		//
	}
}

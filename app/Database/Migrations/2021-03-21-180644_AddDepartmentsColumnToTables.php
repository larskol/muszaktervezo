<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentsColumnToTables extends Migration
{
	public function up()
	{
		$fields = [
			'departments' => [
				'type' => 'json',
				'null' => true
			]
		];
		$this->forge->addColumn('shifts', $fields);
		$this->forge->addColumn('knowledge', $fields);
	}

	public function down()
	{
		//
	}
}

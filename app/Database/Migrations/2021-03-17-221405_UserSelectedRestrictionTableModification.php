<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserSelectedRestrictionTableModification extends Migration
{
	public function up()
	{
		$fields = [
			'created_at' => [
				'type' => 'datetime',
				'null' => true
			],
			'updated_at' => [
				'type' => 'datetime',
				'null' => true
			]
		];
		$this->forge->addColumn('user_selected_restrictions', $fields);
	}

	public function down()
	{
		//
	}
}

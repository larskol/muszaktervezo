<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeUserSelectedRestrictionsValueType extends Migration
{
	public function up()
	{
		$fields = [
			'value_type' => [
					'type' => 'ENUM',
					'constraint' => ['all', 'given_day', 'given_week'],
					'comment' => 'egész hónap, adott nap, adott hét'
			],
		];
		$this->forge->modifyColumn('user_selected_restrictions', $fields);
	}

	public function down()
	{
		//
	}
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewColumnsUserRestrictions extends Migration
{
	public function up()
	{
		$fields = [
			'value_type' => [
				'type' => 'varchar',
				'constraint' => '30',
				'null' => true,
				'after' => 'value',
				'comment' => 'egész hónap, adott nap, adott hét'
			],
			'week_number' => [
				'type' => 'tinyint',
				'null' => true,
				'after' => 'value',
				'comment' => 'adott hét, amelyiken a kiválasztott nap van'
			],
			'week_value' => [
				'type' => 'date',
				'null' => true,
				'after' => 'value',
				'comment' => 'adott hét, amelyiken a kiválasztott nap van'
			],
			'day_value' => [
				'type' => 'date',
				'null' => true,
				'after' => 'value',
				'comment' => 'adott nap'
			],
			
		];
		$this->forge->addColumn('user_selected_restrictions', $fields);
	}

	public function down()
	{
		//
	}
}

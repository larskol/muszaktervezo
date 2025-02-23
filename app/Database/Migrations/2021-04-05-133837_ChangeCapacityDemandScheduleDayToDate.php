<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeCapacityDemandScheduleDayToDate extends Migration
{
	public function up()
	{
		$fields = [
			'day' => [
					'type' => 'date',
					'null' => false
			],
		];
		$this->forge->modifyColumn('capacity_demand', $fields);
		$this->forge->modifyColumn('schedule', $fields);
	}

	public function down()
	{
		//
	}
}

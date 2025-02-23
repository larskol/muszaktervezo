<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestrictionsDropColumn extends Migration
{
	public function up()
	{
		$this->forge->dropColumn('restrictions', 'select_table');
	}

	public function down()
	{
		//
	}
}

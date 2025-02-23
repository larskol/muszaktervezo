<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserKnowledgeLevelsDropColumn extends Migration
{
	public function up()
	{
		$this->forge->dropColumn('user_knowledge_levels', 'id');
	}

	public function down()
	{
		//
	}
}

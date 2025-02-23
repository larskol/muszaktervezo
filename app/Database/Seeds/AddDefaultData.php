<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddDefaultData extends Seeder
{
	public function run()
	{
		$this->call("AddAdmin");
		$this->call("AddDepartments");
		$this->call("AddShifts");
		$this->call("AddRestrictions");
		$this->call("AddKnowledgeLevels");
		$this->call("AddKnowledge");
	}
}

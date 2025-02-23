<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddDepartments extends Seeder
{
	public function run()
	{
		$data = [
			[
				"name" => "IT részleg",
				"created_at" => date("Y-m-d H:i:s")
			]
		];
		$this->db->table("departments")->insertBatch($data);
	}
}

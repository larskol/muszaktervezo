<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddRestrictions extends Seeder
{
	public function run()
	{
		$data = [
			[
				"name" => "Ebben a műszakban szeretnék dolgozni",
				"input_type" => "shifts",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Ebben a műszakban nem szeretnék dolgozni",
				"input_type" => "shifts",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Heti munkanapok száma",
				"input_type" => "input",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Heti egymás utáni munkanapok száma",
				"input_type" => "input",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Heti egymás utáni szabadnapok száma",
				"input_type" => "input",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Az adott személlyel nem szeretnék együtt dolgozni",
				"input_type" => "users",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Ezen a napon szabadságon vagyok",
				"input_type" => "input_date",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "A hét ezen napján nem szeretnék dolgozni",
				"input_type" => "custom_days",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Az adott személlyel szeretnék együtt dolgozni",
				"input_type" => "users",
				"created_at" => date("Y-m-d H:i:s")
			],
		];
		$this->db->table("restrictions")->insertBatch($data);
	}
}

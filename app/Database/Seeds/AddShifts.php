<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddShifts extends Seeder
{
	public function run()
	{
		$data = [
			[
				"name" => "Normál műszak",
				"start_time" => "08:00",
				"end_time" => "16:00",
				"display_order" => 1,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Esti műszak",
				"start_time" => "16:00",
				"end_time" => "00:00",
				"display_order" => 2,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Éjszakai műszak",
				"start_time" => "00:00",
				"end_time" => "08:00",
				"display_order" => 3,
				"created_at" => date("Y-m-d H:i:s")
			]
		];
		$this->db->table("shifts")->insertBatch($data);
	}
}

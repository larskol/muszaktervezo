<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddKnowledgeLevels extends Seeder
{
	public function run()
	{
		$data = [
			[
				"name" => "Kezdő",
				"experience" => 0,
				"display_order" => 1,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Junior",
				"experience" => 1,
				"display_order" => 2,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Medior",
				"experience" => 3,
				"display_order" => 3,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Senior",
				"experience" => 5,
				"display_order" => 4,
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Expert",
				"experience" => 8,
				"display_order" => 5,
				"created_at" => date("Y-m-d H:i:s")
			]
		];
		$this->db->table("knowledge_levels")->insertBatch($data);
	}
}

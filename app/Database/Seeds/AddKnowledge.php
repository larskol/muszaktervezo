<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddKnowledge extends Seeder
{
	public function run()
	{
		$data = [
			[
				"name" => "PHP",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Javascript",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "C",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "C++",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "C#",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "NodeJS",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Python",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Frontend keretrendszer (Vue/Angular/React)",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => ".NET keretrendszer",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "AWS",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Linux (rendszergazda szintű ismeret)",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Windows (rendszergazda szintű ismeret)",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "SEO optimalizálás",
				"created_at" => date("Y-m-d H:i:s")
			],
			[
				"name" => "Microsoft office programok",
				"created_at" => date("Y-m-d H:i:s")
			]
		];
		$this->db->table("knowledge")->insertBatch($data);
	}
}

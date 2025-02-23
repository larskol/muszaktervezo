<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddAdmin extends Seeder
{
	public function run()
	{
		$data = [
			"email" => "admin@servicecenter.com",
			"password" => "FDnajAhLhewmSQ8",
			"created_at" => date("Y-m-d H:i:s"),
			"role" => "admin",
			"firstname" => "Admin",
			"lastname" => "Admin",
			"department_id" => null
		];
		$user = new \App\Entities\User($data);

		$userModel = new \App\Models\User();
		$userModel->insert($user);
	}
}

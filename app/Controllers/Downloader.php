<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Downloader extends BaseController
{
	public function admin($type)
	{
		switch ($type) {
			case 'user-sample':
				return $this->response->download('../files/csv/user_upload_sample.csv', null)->setFileName('user_upload_sample.csv');
				break;
			case 'user-example':
				return $this->response->download('../files/csv/user_upload_example.csv', null)->setFileName('user_upload_example.csv');
				break;
			case 'point-sample':
				return $this->response->download('../files/csv/user_update_points_sample.csv', null)->setFileName('user_points_sample.csv');
				break;
			case 'point-example':
				return $this->response->download('../files/csv/user_update_points_example.csv', null)->setFileName('user_points_example.csv');
				break;			
		}
	}

	public function departmentAdmin($type)
	{
		switch ($type) {
			case 'user-sample':
				return $this->response->download('../files/csv/dep_admin_user_upload_sample.csv', null)->setFileName('user_upload_sample.csv');
				break;
			case 'user-example':
				return $this->response->download('../files/csv/dep_admin_user_upload_example.csv', null)->setFileName('user_upload_example.csv');
				break;
			case 'point-sample':
				return $this->response->download('../files/csv/user_update_points_sample.csv', null)->setFileName('user_points_sample.csv');
				break;
			case 'point-example':
				return $this->response->download('../files/csv/user_update_points_example.csv', null)->setFileName('user_points_example.csv');
				break;
			case 'capacity-demand-sample':
				return $this->response->download('../files/csv/capacity_demand_sample.csv', null)->setFileName('capacity_demand_sample.csv');
				break;
			case 'capacity-demand-example':
				return $this->response->download('../files/csv/capacity_demand_example.csv', null)->setFileName('capacity_demand_example.csv');
				break;
		}
	}
}

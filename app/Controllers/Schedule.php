<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Schedule extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "schedule";
		$this->viewDir = "schedule";
	}

	public function index()
	{
		$request = service("request");
		$validation = service("validation");
		$month = (new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m");
		if($validation->check($request->getGet("month"), "valid_date[Y-m]")){
			$month = $request->getGet("month");
		}
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "schedule",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleSchedule')." - ".$month,
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleSchedule")." - ".$month,
			"subtitle" => lang("Site.siteTitleScheduleSubtitle")
		];

		$date = new \DateTime($month);
		$start = $date->format("Y-m-01");
		$end = $date->add(new \DateInterval("P1M"))->format("Y-m-d");
		$days = [];
		//tömb feltöltése a hónap napjaival
		while($start < $end){
			$days[$start]["items"] = [];
			$days[$start]["day"] = lang("Site.siteDay".(new \DateTime($start))->format("N"));
			$start = (new \DateTime($start))->add(new \DateInterval("P1D"))->format("Y-m-d");
		}
		
		$schedule = new \App\Models\Schedule();
		$items = $schedule->getItemsOneMonth($session->get("userCurrentDepartmentId"), true, $month);
		
		if($items){
			foreach($items as $value){
				$days[$value->day]["items"][$value->shift_id]["name"] = $value->shift_name;
				$days[$value->day]["items"][$value->shift_id]["time"] = $value->shift_time;
				$days[$value->day]["items"][$value->shift_id]["items"][] = [
					"name" => $value->user_name,
					"email" => $value->user_email
				];
			}
		}else{
			$days = [];
		}

		$formContent["selectedMonth"] = $month;
		$formContent["items"] = $days;
		$formContent["dates"] = $schedule->getDates($session->get("userCurrentDepartmentId"));
		$pageContent["content"] = view("{$this->viewDir}/schedule", $formContent);
		echo view("layout", $pageContent);
	}

	public function admin()
	{
		$request = service("request");
		$validation = service("validation");
		$month = (new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m");
		if($validation->check($request->getGet("month"), "valid_date[Y-m]")){
			$month = $request->getGet("month");
		}
		$selectedDepartment = false;
		if($validation->check($request->getGet("department"), "required|is_not_unique[departments.id]")){
			$selectedDepartment = $request->getGet("department");
		}
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => "admin-schedule"
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleSchedule')." - ".$month,
			"userLoginInfo" => get_department_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleSchedule")." - ".$month,
			"subtitle" => lang("Site.siteTitleScheduleSubtitle")
		];

		$date = new \DateTime($month);
		$start = $date->format("Y-m-01");
		$end = $date->add(new \DateInterval("P1M"))->format("Y-m-d");
		$days = [];
		//tömb feltöltése a hónap napjaival
		while($start < $end){
			$days[$start]["items"] = [];
			$days[$start]["day"] = lang("Site.siteDay".(new \DateTime($start))->format("N"));
			$start = (new \DateTime($start))->add(new \DateInterval("P1D"))->format("Y-m-d");
		}
		
		$schedule = new \App\Models\Schedule();
		$items = $schedule->getItemsOneMonth($selectedDepartment, true, $month);
		
		if($items){
			foreach($items as $value){
				$days[$value->day]["items"][$value->shift_id]["name"] = $value->shift_name;
				$days[$value->day]["items"][$value->shift_id]["time"] = $value->shift_time;
				$days[$value->day]["items"][$value->shift_id]["items"][] = [
					"name" => $value->user_name,
					"email" => $value->user_email
				];
			}
		}else{
			$days = [];
		}

		$department = new \App\Models\Department();
		$formContent["departments"] = $department->findAll();
		$formContent["selectedDepartment"] = $selectedDepartment;
		$formContent["selectedMonth"] = $month;
		$formContent["items"] = $days;
		$formContent["dates"] = $schedule->getDates($selectedDepartment);
		$pageContent["content"] = view("{$this->viewDir}/admin", $formContent);
		echo view("layout", $pageContent);
	}

	public function user()
	{
		$request = service("request");
		$validation = service("validation");
		$month = (new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m");
		if($validation->check($request->getGet("month"), "valid_date[Y-m]")){
			$month = $request->getGet("month");
		}
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => "user-schedule"
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleSchedule')." - ".$month,
			"userLoginInfo" => get_department_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleSchedule")." - ".$month,
			"subtitle" => lang("Site.siteTitleScheduleSubtitle")
		];

		$date = new \DateTime($month);
		$start = $date->format("Y-m-01");
		$end = $date->add(new \DateInterval("P1M"))->format("Y-m-d");
		$days = [];
		//tömb feltöltése a hónap napjaival
		while($start < $end){
			$days[$start]["items"] = [];
			$days[$start]["day"] = lang("Site.siteDay".(new \DateTime($start))->format("N"));
			$start = (new \DateTime($start))->add(new \DateInterval("P1D"))->format("Y-m-d");
		}
		
		$schedule = new \App\Models\Schedule();
		$items = $schedule->getItemsOneMonth($session->get("userDepartment"), true, $month);
		
		if($items){
			foreach($items as $value){
				$days[$value->day]["items"][$value->shift_id]["name"] = $value->shift_name;
				$days[$value->day]["items"][$value->shift_id]["time"] = $value->shift_time;
				$days[$value->day]["items"][$value->shift_id]["items"][] = [
					"name" => $value->user_name,
					"email" => $value->user_email
				];
			}
		}else{
			$days = [];
		}

		$formContent["selectedMonth"] = $month;
		$formContent["items"] = $days;
		$formContent["dates"] = $schedule->getDates($session->get("userDepartment"));
		$pageContent["content"] = view("{$this->viewDir}/user", $formContent);
		echo view("layout", $pageContent);
	}

	public function assign()
	{
		$response = [
			"status" => true,
			"message" => ""
		];
		$session = service("session");
		$request = service("request");
		if(!is_null($request->getPost("assign"))){
			$date = new \DateTime();
			$currentMonth = $date->format("Y-m");
			$nextMonth = $date->add(new \DateInterval("P1M"))->format("Y-m");
			$schedule = new \App\Models\Schedule();
			$cd = new \App\Models\CapacityDemand();
			$items = [];
			//melyik havi beosztás kerül elkészítésre
			$month = null;
			//lekérjük a jelenlegi havi beosztást
			$sch = $schedule->getItemsOneMonth($session->get("userCurrentDepartmentId"), false, $currentMonth);
			//ha nincs, akkor lekérjük az igényeket az elkészítéshez
			if(!$sch){
				$items = $cd->getItemsOneMonth($session->get("userCurrentDepartmentId"), true, $currentMonth);
				if($items){
					$month = $currentMonth;
				}
			}

			//ha már van beosztás, vagy nincs megadott igény a jelenlegi hónapban, akkor a következő hónapot vizsgáljuk
			if(is_null($month)){
				$sch = $schedule->getItemsOneMonth($session->get("userCurrentDepartmentId"), false, $nextMonth);
				if(!$sch){
					$items = $cd->getItemsOneMonth($session->get("userCurrentDepartmentId"), true, $nextMonth);
					if($items){
						$month = $nextMonth;
					}
				}
			}

			//ha van megadva igény, elkészíthető a beosztás
			if(!is_null($month) && !empty($items)){
				//beosztás
				$userRestriction = new \App\Models\UserRestriction();
				$user = new \App\Models\User();
				$skill = new \App\Models\Skill();
				//korlátozások lekérése
				$prevMonth = (new \DateTime($month))->sub(new \DateInterval("P1M"));
				$employees = $user->select("id, weekly_work_hours")->where("department_id", $session->get("userCurrentDepartmentId"))->asArray()->findAll();
				//részleghez tartozó felhasználók id-je
				$employeeIds = array_column($employees, "id");
				//felhasználók korlátozásai
				$restrictions = $userRestriction->asArray()->whereIn("user_id", $employeeIds)->where(["created_at >=" => $prevMonth->format("Y-m-01"), "created_at <" => (new \DateTime($prevMonth->format("Y-m-01")))->add(new \DateInterval("P1M"))->format("Y-m-d")])->findAll();
				//felhasználók ismeretei
				$skills = $skill->asArray()->whereIn("user_id", $employeeIds)->findAll();
				set_time_limit(15*60);
				$assignment = $schedule->scheduling($items, $restrictions, $employees, $skills);
				foreach ($assignment as $key => $value) {
					$assignment[$key]["department_id"] = $session->get("userCurrentDepartmentId");
				}
				$result = $schedule->insertBatch($assignment);
				if(is_numeric($result)){
					$messageContent = [
						"type" => "success",
						"message" => lang("Messages.messageAssigningStarted"),
						'errors' => ""
					];
					$response["message"] = view("message", $messageContent);
					$response["refresh"] = true;
				}else{
					$messageContent = [
						"type" => "warning",
						"message" => lang("Messages.messageAlredyDoneOrNoDataProvided"),
						'errors' => ""
					];
					$response["message"] = view("message", $messageContent);
				}
			}else{
				$messageContent = [
					"type" => "warning",
					"message" => lang("Messages.messageDefault"),
					'errors' => ""
				];
				$response["message"] = view("message", $messageContent);
			}

			print json_encode($response);
		}
	}
}

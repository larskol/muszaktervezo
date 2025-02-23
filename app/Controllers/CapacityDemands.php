<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CapacityDemand;

class CapacityDemands extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "capacity-demands";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "capacity_demands";
	}

	/**
	 * Adatbázisban szerepló, kiválasztott részleghez tartozó kapacitás igények listája
	 */
	public function index()
	{
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->listUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleCapacityDemands'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleCapacityDemands"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$cd = new CapacityDemand();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderDay"), lang("Table.tableHeaderDepartment"), lang("Table.tableHeaderKnowledge"), lang("Table.tableHeaderKnowledgeLevel"), lang("Table.tableHeaderAmount"), lang("Table.tableHeaderCreatedAt")]);
        $items = $cd->select("'' AS dtDummy, capacity_demand.id, capacity_demand.day, departments.name AS department_name, knowledge.name AS knowledge_name, knowledge_levels.name AS knowledge_level_name, capacity_demand.amount, capacity_demand.created_at")
		->where("capacity_demand.department_id", $session->get("userCurrentDepartmentId"))
		->join("departments", "capacity_demand.department_id=departments.id", "left outer")
		->join("knowledge", "capacity_demand.knowledge_id=knowledge.id", "left outer")
		->join("knowledge_levels", "capacity_demand.knowledge_level_id=knowledge_levels.id", "left outer")
		->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 2;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Új kapacitás igény hozzáadása a kiválasztott részleghez
	 */
	public function create(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddCapacityDemand'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddCapacityDemand"),
			//"subtitle" => lang("Site.siteTitleAddCapacityDemand"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$shift = new \App\Models\Shift();
		$formContent["shifts"] = $shift->getAvailableShifts($session->get("userCurrentDepartmentId"));
		$knowledge = new \App\Models\Knowledge();
		$formContent["knowledge"] = $knowledge->getAvailableKnowledge($session->get("userCurrentDepartmentId"));
		$knowledgeLevel = new \App\Models\KnowledgeLevel();
		$formContent["knowledgeLevels"] = $knowledgeLevel->orderBy("experience", "asc")->findAll();
		$roles = new \App\Libraries\RoleLib();
		$formContent["userRoles"] = array_reverse($roles->roles);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Kapacitás igény szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditCapacityDemand'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditCapacityDemand"),
			//"subtitle" => lang("Site.siteTitleEditUser"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$shift = new \App\Models\Shift();
		$formContent["shifts"] = $shift->getAvailableShifts($session->get("userCurrentDepartmentId"));
		$knowledge = new \App\Models\Knowledge();
		$formContent["knowledge"] = $knowledge->getAvailableKnowledge($session->get("userCurrentDepartmentId"));
		$knowledgeLevel = new \App\Models\KnowledgeLevel();
		$formContent["knowledgeLevels"] = $knowledgeLevel->orderBy("experience", "asc")->findAll();
		$cd = new CapacityDemand();
		$formContent["item"] = $cd->where("department_id", $session->get("userCurrentDepartmentId"))->find($id);
		if(!$formContent["item"]){
			return redirect()->to(site_url("{$this->controllerUrl}/{$this->createUrl}"));
		}
		$roles = new \App\Libraries\RoleLib();
		$formContent["userRoles"] = array_reverse($roles->roles);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * kapacitás igény(ek) törlése
	 */
	public function delete(){
		$session = session();
		$response = [
			"status" => false,
			"message" => "",
		];
		
		$request = service('request');
		//Csak számok lehetnek a tömbben
		$filteredItems = array_filter($request->getJsonVar("items"), "ctype_digit");
			if(count($filteredItems) > 0){
				$cd = new CapacityDemand();
				if($cd->where("department_id", $session->get("userCurrentDepartmentId"))->whereIn("id", $filteredItems)->delete(null)){
					$response["status"] = true;
					$messageContent = [
						"type" => "success",
						"message" => lang("Messages.messageDeleteSuccess")
					];
					$response["message"] = view("message", $messageContent);
				}else{
					$messageContent = [
						"type" => "warning",
						"message" => lang("Messages.messageDefault")
					];
					$response["message"] = view("message", $messageContent);
				}
			}

		echo json_encode($response);
	}

	/**
	 * (Ajax hívás) kapacitás igény mentése.
	 * Ha nincs megadva ID, akkor új kapacitás igény hozzáadása,
	 * ha van megadva ID, akkor az adott ID-vel rendelkező kapacitás igény szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$session = session();
		$cd = new CapacityDemand();
		//csak a hozzá tartozó részlegekbe tartozó felhasználókat módosíthatja
		$validationRules = [
			"day" => [
				"label" => "Form.formDay",
				"rules" => "required|valid_date[Y-m-d]"
			],
			"knowledge_id" => [
				"label" => "Form.formKnowledge",
				"rules" => "required|is_allowed_item[knowledge]"
			],
			"knowledge_level_id" => [
				"label" => "Form.formExperience",
				"rules" => "permit_empty|is_not_unique[knowledge_levels.id]"
			],
			"shift_id" => [
				"label" => "Form.formShift",
				"rules" => "required|is_allowed_item[shift]"
			],
			"amount" => [
				"label" => "Form.formAmount",
				"rules" => "required|is_natural_no_zero"
			]
		];
		if($this->validate($validationRules)){
			$item = new \App\Entities\CapacityDemand();
			$item->id = $request->getPost("id");
			$item->department_id = $session->get("userCurrentDepartmentId");
			$item->day = $request->getPost("day");
			$item->knowledge_id = $request->getPost("knowledge_id");
			$item->knowledge_level_id = ($request->getPost("knowledge_level_id") == "")  ? null : $request->getPost("knowledge_level_id");
			$item->shift_id = $request->getPost("shift_id");
			$item->amount = $request->getPost("amount");
			if(is_numeric($item->id)){
				$result = $cd->where("department_id", $session->get("userCurrentDepartmentId"))->save($item);
			}else{
				$result = $cd->save($item);
			}
			if($result){
				$response["status"] = true;
				$messageContent = [
					"type" => "success",
					"message" => lang("Messages.messageSaveSuccess")
				];
				$response["message"] = view("message", $messageContent);
			}else{
				$response["status"] = false;
				$messageContent = [
					"type" => "warning",
					"message" => lang("Messages.messageDefault")
				];
				$response["message"] = view("message", $messageContent);
			}
		}else{
			$response["errors"] = $this->validator->getErrors();
		}

		echo json_encode($response);
	}

	/**
	 * Új kapacitás igény feltöltése CSV fileból a jelenlegi részleghez
	 */
	public function upload(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "upload",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleUploadCapacityDemand'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$infoContent = [
			"sampleUrl" => "/download/csv-dep/capacity-demand-sample",
			"exampleUrl" => "/download/csv-dep/capacity-demand-example",
			"role" => "department_admin"
		];
		$formContent = [
			"title" => lang("Site.siteTitleUploadCapacityDemand"),
			"subtitle" => "",
			"backButtonUrl" => "/{$this->controllerUrl}",
			"uploadUrl" => "/{$this->controllerUrl}/upload-file",
			"infoText" => view("{$this->viewDir}/csv_file_info", $infoContent)
		];
		$pageContent["content"] = view("csv_upload", $formContent);
		echo view("layout", $pageContent);
	}

	public function uploadFile(){
		$response = [
			"status" => false,
			"successMessage" => "",
			"errorMessage" => ""
		];
		$validationRules = [
			"csv" => [
				"label" => "Form.formFile",
				"rules" => "ext_in[csv,csv]"
				]
		];
		if($this->validate($validationRules)){
			if(isset($_FILES["csv"]) && isset($_FILES["csv"]["tmp_name"])){
				helper("csv_reader");
				$headers = [
					"day",
					"shift_id",
					"knowledge_id",
					"knowledge_level_id",
					"amount"
				];
				$csvData = readCSV($_FILES["csv"]["tmp_name"], $headers);
				if(!empty($csvData)){
					$session = session();
					$response["status"] = true;
					$items = (new \App\Models\Shift())->getAvailableShifts($session->get("userCurrentDepartmentId"));
					$shiftsArray = [];
					foreach($items as $key => $value){
						$shiftsArray[$value->name] = $value->id;
					}
					$items = (new \App\Models\Knowledge())->getAvailableKnowledge($session->get("userCurrentDepartmentId"));
					$knowledgeArray = [];
					foreach($items as $key => $value){
						$knowledgeArray[$value->name] = $value->id;
					}
					$items = (new \App\Models\KnowledgeLevel())->findAll();
					$knowledgeLevelsArray = [];
					foreach($items as $key => $value){
						$knowledgeLevelsArray[$value->name] = $value->id;
					}
					$insert = [];
					$failedString = "";
					$validation =  \Config\Services::validation();
					$rules = [
						"day" => [
							"label" => "Form.formDay",
							"rules" => "required|valid_date[Y-m-d]"
						],
						"knowledge_id" => [
							"label" => "Form.formKnowledge",
							"rules" => "required|is_allowed_item[knowledge]"
						],
						"knowledge_level_id" => [
							"label" => "Form.formExperience",
							"rules" => "permit_empty|is_not_unique[knowledge_levels.id]"
						],
						"shift_id" => [
							"label" => "Form.formShift",
							"rules" => "required|is_allowed_item[shift]"
						],
						"amount" => [
							"label" => "Form.formAmount",
							"rules" => "required|is_natural_no_zero"
						]
					];
					foreach($csvData as $key => $item){
							$item["department_id"] = $session->get("userCurrentDepartmentId");
							// ha jól adta meg a műszakot, beállítjuk
							if(isset($shiftsArray[$item["shift_id"]])){
								$item["shift_id"] = $shiftsArray[$item["shift_id"]];
							}
							// ha jól adta meg az ismeretet, beállítjuk
							if(isset($knowledgeArray[$item["knowledge_id"]])){
								$item["knowledge_id"] = $knowledgeArray[$item["knowledge_id"]];
							}
							// ha jól adta meg a tudásszintet, beállítjuk
							if(isset($knowledgeLevelsArray[$item["knowledge_level_id"]])){
								$item["knowledge_level_id"] = $knowledgeLevelsArray[$item["knowledge_level_id"]];
							}
							//Ha nincs megadva tudásszint, akkor null
							if($item["knowledge_level_id"] == ""){
								$item["knowledge_level_id"] = null;
							}
							
							$validation->reset();
							$validation->setRules($rules);
							if($validation->run($item)){
								$insert[] = new \App\Entities\CapacityDemand($item);
							}else{
								$failedString .= lang("Messages.messageFileRowError", [($key + 1), $item['day'], $validation->listErrors()]);
							}					
					}

					//ha van hiba kiírjuk, ha minden rendben, csak akkor insert
					if($failedString != ""){
						$messageContent = [
							"type" => "danger",
							"message" => $failedString,
							'errors' => ""
						];
						$response["errorMessage"] = view("message", $messageContent);
					}else{
						if(!empty($insert)){
							$cd = new CapacityDemand();
							$count = $cd->insertBatch($insert);
							if($count !== false){
								$messageContent = [
									"type" => "success",
									"message" => lang("Messages.messageRowsAdded", [$count]),
									'errors' => ""
								];
								$response["successMessage"] = view("message", $messageContent);
							}
						}
					}					
				}else{
					$messageContent = [
						"type" => "danger",
						"message" => lang("Messages.messageFileError"),
						'errors' => ""
					];
					$response["errorMessage"] = view("message", $messageContent);
				}
			}
		}else{
			$messageContent = [
				"type" => "danger",
				"message" => lang("Messages.messageUnsupportedFile"),
				'errors' => ""
			];
			$response = [
				"status" => false,
				"successMessage" => "",
				"errorMessage" => view("message", $messageContent)
			];
		}

		echo json_encode($response);
	}

	/**
	 * Korábbi kapacitás igények
	 */
	public function details(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "details",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleCapacityDemands'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleCapacityDemands"),
			//"subtitle" => lang("Site.siteTitleEditUser"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$request = service("request");
		$validation = service("validation");
		//következő hónap, mert azt kell ebben a hónapban megadni, ez a jelenlegi időszak
		$selectedMonth = (new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m");
		if($validation->check($request->getGet("month"), "valid_date[Y-m]")){
			$selectedMonth = $request->getGet("month");
		}

		$date = new \DateTime($selectedMonth);
		$start = $date->format("Y-m-01");
		$end = $date->add(new \DateInterval("P1M"))->format("Y-m-d");
		$days = [];
		//tömb feltöltése a hónap napjaival
		while($start < $end){
			$days[$start]["items"] = [];
			$days[$start]["day"] = lang("Site.siteDay".(new \DateTime($start))->format("N"));
			$start = (new \DateTime($start))->add(new \DateInterval("P1D"))->format("Y-m-d");
		}

		$cd = new CapacityDemand();
		$items = $cd->getItemsOneMonth($session->get("userCurrentDepartmentId"), true, $selectedMonth);
		if($items){
			foreach($items as $value){
				$days[$value->day]["items"][] = $value;
			}
		}

		$formContent["selectedMonth"] = $selectedMonth;
		$formContent["items"] = $days;
		$formContent["dates"] = $cd->getDates($session->get("userCurrentDepartmentId"));
		$pageContent["content"] = view("{$this->viewDir}/details", $formContent);
		echo view("layout", $pageContent);	
	}
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class DepartmentUsers extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "department-users";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "users/department";
	}

	/**
	 * Adatbázisban szerepló, kiválasztott részleghez tartozó felhasználók listája
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleUsers'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleUsers"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$user = new User();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderEmail"), lang("Table.tableHeaderWeeklyWorkHours"), lang("Table.tableHeaderPaidAnnualLeave"), lang("Table.tableHeaderBonusPoint"), lang("Table.tableHeaderCreatedAt")]);
        $items = $user->select("'' AS dtDummy, id, CONCAT_WS(' ',lastname,' ',firstname) AS name, email, weekly_work_hours, paid_annual_leave, bonus_point,  created_at")
		->where("department_id", $session->get("userCurrentDepartmentId"))
		->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 7;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Új felhasználó hozzáadása a kiválasztott részleghez
	 */
	public function create(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddUser'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddUser"),
			"subtitle" => lang("Site.siteTitleAddUser"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$departments = new \App\Models\Department();
		$formContent["departments"] = $departments->findAll();
		$roles = new \App\Libraries\RoleLib();
		$formContent["userRoles"] = array_reverse($roles->roles);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Felhasználó szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditUser'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditUser"),
			"subtitle" => lang("Site.siteTitleEditUser"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$departments = new \App\Models\Department();
		$formContent["departments"] = $departments->findAll();
		$user = new User();
		$formContent["item"] = $user->where("department_id", $session->get("userCurrentDepartmentId"))->find($id);
		if(!$formContent["item"]){
			return redirect()->to(site_url("{$this->controllerUrl}/{$this->createUrl}"));
		}
		$roles = new \App\Libraries\RoleLib();
		$formContent["userRoles"] = array_reverse($roles->roles);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * Felhasználó(k) törlése
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
		//az 1-es ID-jű felhasználó nem törölhető (admin)
		$roles = new \App\Libraries\RoleLib();
		$userCantBeDeletedWarning = in_array($roles->systemAdminId, $filteredItems);
		$user = new User();
		$filteredItems = array_diff($filteredItems, [$roles->systemAdminId]);
		if(!$userCantBeDeletedWarning){
			$toBeDeleted = array_intersect($filteredItems, array_column($user->getDepartmentUsers($session->get("userCurrentDepartmentId")), "id"));
			if(count($toBeDeleted) > 0){
				//csak azok az id-k amelyeket törölhet, mert abba a részlegbe tartoznak
				$user->transStart();
				$user->where("department_id", $session->get("userCurrentDepartmentId"))->whereIn("id", $toBeDeleted)->delete(null);
				$user->removeUserData($filteredItems);
				$transRes = $transRes = $user->transComplete();
				if($transRes){
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
			}else{
				$messageContent = [
					"type" => "warning",
					"message" => lang("Messages.messageCantBeDeleted")
				];
				$response["message"] = view("message", $messageContent);
			}
		}else{		
			$messageContent = [
				"type" => "warning",
				"message" => lang("Messages.messageUserCantBeDeleted")
			];
			$response["message"] .= view("message", $messageContent);
		}
		
		echo json_encode($response);
	}

	/**
	 * (Ajax hívás) Felhasználó mentése.
	 * Ha nincs megadva ID, akkor új felhasználó hozzáadása,
	 * ha van megadva ID, akkor az adott ID-vel rendelkező felhasználó szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');

		$roles = new \App\Libraries\RoleLib();
		$session = session();
		$user = new User();
		//csak a hozzá tartozó részlegekbe tartozó felhasználókat módosíthatja
		$idList = implode(",", array_column($user->getDepartmentUsers($session->get("userCurrentDepartmentId")), "id"));
		if($idList !== ""){
			$idRules = "permit_empty|in_list[{$idList}]";
		}else{
			$idRules = "";
		}
		$roles->systemAdminId;
		$validationRules = [
			"id" => [
				"label" => "Form.formId",
				"rules" => $idRules,
				"errors" => [
					"in_list" => lang("Messages.messageInvalidId")
				]
			],
			"firstname" => [
				"label" => "Form.formFirstname",
				"rules" => "required|max_length[100]"
			],
			"lastname" => [
				"label" => "Form.formLastname",
				"rules" => "required|max_length[100]"
			],
			"email" => [
				"label" => "Form.formEmail",
				"rules" => "required|max_length[255]|valid_email|is_unique[users.email,id,{id}]"
			],
			"weekly_work_hours" => [
				"label" => "Form.formWeeklyWorkHours",
				"rules" => "required|numeric"
			],
			"paid_annual_leave" => [
				"label" => "Form.formPaidAnnualLeave",
				"rules" => "required|numeric"
			],
			"bonus_point" => [
				"label" => "Form.formBonusPoint",
				"rules" => "required|is_natural"
			]
		];
		$id = $request->getPost("id");
		if($id && is_numeric($id)){
			//létező felhasználó esetén ha van megadva jelszó, csak akkor kell validálni és módosítani
			$password = $request->getPost("password");
			if($password != ""){
				$validationRules["password"] = [
					"label" => "Form.formPassword",
					"rules" => "required|min_length[6]"
					]
				;
				$validationRules["password_confirm"] = [
					"label" => "Form.formPasswordConfirm",
					"rules" => "matches[password]"
				];
			}
		}else{
			//új felhasználó esetén kötelező a jelszó
			$validationRules["password"] = [
				"label" => "Form.formPassword",
				"rules" => "required|min_length[6]"
				];
			$validationRules["password_confirm"] = [
				"label" => "Form.formPasswordConfirm",
				"rules" => "matches[password]"
			];
		}
		if($this->validate($validationRules)){
			$item = new \App\Entities\User();
			$item->id = $request->getPost("id");
			$item->firstname = $request->getPost("firstname");
			$item->lastname = $request->getPost("lastname");
			$item->email = $request->getPost("email");
			$item->password = $request->getPost("password");
			$item->weekly_work_hours = $request->getPost("weekly_work_hours");
			$item->paid_annual_leave = $request->getPost("paid_annual_leave");
			$item->bonus_point = $request->getPost("bonus_point");
			$item->department_id = $session->get("userCurrentDepartmentId");
			if(is_numeric($item->id)){
				$result = $user->where("department_id", $session->get("userCurrentDepartmentId"))->save($item);
			}else{
				$result = $user->save($item);
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
	 * Részleg váltása
	 */
	public function switchDepartment(){
		$session = session();
		$response = [
			"status" => false,
			"successMessage" => "",
			"errorMessage" => ""
		];
		$request = service("request");
		$id = $request->getPost("department");
		if(in_array($id, $session->get("userDepartments"))){
			$response["status"] = true;
			$session->set("userCurrentDepartmentId", $id);
		}
		print json_encode($response);
	}

	/**
	 * Új felhasználók hozzáadása CSV fileból a jelenlegi részleghez
	 */
	public function upload(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "upload",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddUserFromFile'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$infoContent = [
			"sampleUrl" => "/download/csv-dep/user-sample",
			"exampleUrl" => "/download/csv-dep/user-example",
			"role" => "department_admin"
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddUserFromFile"),
			"subtitle" => "",
			"backButtonUrl" => "/{$this->controllerUrl}",
			"uploadUrl" => "/{$this->controllerUrl}/upload-users",
			"infoText" => view("users/csv_info/admin_user_csv_info", $infoContent)
		];
		$pageContent["content"] = view("csv_upload", $formContent);
		echo view("layout", $pageContent);
	}

	public function addUsersCSV(){
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
					"email",
					"password",
					"lastname",
					"firstname",
					"weekly_work_hours",
					"paid_annual_leave",
					"bonus_point"
				];
				$csvData = readCSV($_FILES["csv"]["tmp_name"], $headers);
				if(!empty($csvData)){
					$session = session();
					$response["status"] = true;
					$insert = [];
					$failedString = "";
					$validation =  \Config\Services::validation();
					$rules = [
						"firstname" => [
							"label" => "Form.formFirstname",
							"rules" => "required|max_length[100]"
						],
						"lastname" => [
							"label" => "Form.formLastname",
							"rules" => "required|max_length[100]"
						],
						"email" => [
							"label" => "Form.formEmail",
							"rules" => "required|max_length[255]|valid_email|is_unique[users.email,id,{id}]"
						],
						"password" => [
							"label" => "Form.formPassword",
							"rules" => "required|min_length[6]"
						],
						"weekly_work_hours" => [
							"label" => "Form.formWeeklyWorkHours",
							"rules" => "required|numeric"
						],
						"paid_annual_leave" => [
							"label" => "Form.formPaidAnnualLeave",
							"rules" => "required|numeric"
						],
						"bonus_point" => [
							"label" => "Form.formBonusPoint",
							"rules" => "required|is_natural"
						]
					];
					foreach($csvData as $key => $item){
							$item["department_id"] = $session->get("userCurrentDepartmentId");
							$item["weekly_work_hours"] = str_replace(",", ".", $item["weekly_work_hours"]);
							$item["paid_annual_leave"] = str_replace(",", ".", $item["paid_annual_leave"]);
							$validation->reset();
							$validation->setRules($rules);
							if($validation->run($item)){
								$insert[] = new \App\Entities\User($item);
							}else{
								$failedString .= lang("Messages.messageFileRowError", [($key + 1), $item['email'], $validation->listErrors()]);
							}					
					}

					if(!empty($insert)){
						$user = new User();
						$count = $user->insertBatch($insert);
						if($count !== false){
							$messageContent = [
								"type" => "success",
								"message" => lang("Messages.messageRowsAdded", [$count]),
								'errors' => ""
							];
							$response["successMessage"] = view("message", $messageContent);
						}
					}

					if($failedString != ""){
						$messageContent = [
							"type" => "danger",
							"message" => $failedString,
							'errors' => ""
						];
						$response["errorMessage"] = view("message", $messageContent);
					}
					
				}else{
					$messageContent = [
						"type" => "danger",
						"message" => lang("Messages.messageFileError"),
						'errors' => ""
					];
					$response["errorMessage"] = view("message", $messageContent);
				}
			}else{

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
	 * Felhasználók - tallérok feltöltése
	 */
	public function updateBonusPoints(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "update-points",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleUpdateUserBonusPoints'),
			"userLoginInfo" => get_department_admin_login_info_text(),
			"departmentSelector" => get_department_select_fields()
		];
		$infoContent = [
			"sampleUrl" => "/download/csv-dep/point-sample",
			"exampleUrl" => "/download/csv-dep/point-example",
			"role" => "department_admin"
		];
		$formContent = [
			"title" => lang("Site.siteTitleUpdateUserBonusPoints"),
			"subtitle" => "",
			"backButtonUrl" => "/{$this->controllerUrl}",
			"uploadUrl" => "/{$this->controllerUrl}/upload-points",
			"infoText" => view("users/csv_info/admin_points_csv_info", $infoContent)
		];
		$pageContent["content"] = view("csv_upload", $formContent);
		echo view("layout", $pageContent);
	}

	public function addBonusPointsCSV(){
		$response = [
			"status" => false,
			"successMessage" => "",
			"errorMessage" => ""
		];
		$validationRules = [
			"csv" => "ext_in[csv,csv]"
		];
		if($this->validate($validationRules)){
			if(isset($_FILES["csv"]) && isset($_FILES["csv"]["tmp_name"])){
				helper("csv_reader");
				$headers = [
					"email",
					"bonus_point"
				];
				$csvData = readCSV($_FILES["csv"]["tmp_name"], $headers);
				if(!empty($csvData)){
					$session = session();
					$response["status"] = true;
					$user = new User();
					$roles = new \App\Libraries\RoleLib();
					$users = $user->select("email")->where(["id !=" => $roles->systemAdminId])->where("department_id", $session->get("userCurrentDepartmentId"))->asArray()->findAll();
					$users = array_column($users, "email");
					$emailString = implode(",", $users);
					$update = [];
					$failedString = "";
					$validation =  \Config\Services::validation();
					$rules = [
						"email" => [
							"label" => "Form.formEmail",
							"rules" => "required|max_length[255]|valid_email|in_list[{$emailString}]"
						],
						"bonus_point" => [
							"label" => "Form.formBonusPoint",
							"rules" => "required|is_natural"
						]
					];
					foreach($csvData as $key => $item){
						$validation->reset();
						$validation->setRules($rules);
						if($validation->run($item)){
							$update[] = new \App\Entities\User($item);
						}else{
							$failedString .= lang("Messages.messageFileRowError", [($key + 1), $item['email'], $validation->listErrors()]);
						}
						
					}

					if(!empty($update)){
						$count = $user->updateBatch($update, "email");
						if($count !== false){
							$messageContent = [
								"type" => "success",
								"message" => lang("Messages.messageRowsUpdated", [$count]),
								'errors' => ""
							];
							$response["successMessage"] = view("message", $messageContent);
						}
					}

					if($failedString != ""){
						$messageContent = [
							"type" => "danger",
							"message" => $failedString,
							'errors' => ""
						];
						$response["errorMessage"] = view("message", $messageContent);
					}
					
				}else{
					$messageContent = [
						"type" => "danger",
						"message" => lang("Messages.messageFileError"),
						'errors' => ""
					];
					$response["errorMessage"] = view("message", $messageContent);
				}
			}else{

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
}

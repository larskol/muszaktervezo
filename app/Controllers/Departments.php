<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Department;

class Departments extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "departments";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "departments";
	}
	
	/**
	 * Adatbázisban szerepló részlegek listája
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleDepartments'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleDepartments"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$department = new Department();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderCreatedAt")]);
        $items = $department->select("'' AS dtDummy, id, name, created_at")->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 3;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Új részleg hozzáadása
	 */
	public function create(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddDepartment'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddDepartment"),
			//"subtitle" => lang("Site.siteTitleAddDepartment"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Részleg szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditDepartment'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditDepartment"),
			//"subtitle" => lang("Site.siteTitleEditDepartment"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];

		$department = new Department();
		$formContent["item"] = $department->find($id);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * Részleg(ek) szerkesztése
	 */
	public function delete(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		//Csak számok lehetnek a tömbben
		$filteredItems = array_filter($request->getJsonVar("items"), "ctype_digit");
		if(count($filteredItems) > 0){
			$department = new Department();
			if($department->isEmpty($filteredItems)){
				if($department->delete($filteredItems)){
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
					"type" => "danger",
					"message" => lang("Messages.messageDeleteEmptyDepartmentOnly")
				];
				$response["message"] = view("message", $messageContent);
			}
		}
		
		echo json_encode($response);
	}

	/**
	 * (Ajax hívás) Részleg mentése.
	 * Ha nincs megadva ID, akkor új részleg hozzáadása,
	 * ha van megadva ID, akkor az adott ID-vel rendelkező részleg szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$department = new Department();
		if($department->validate($request->getPost())){
			$item = new \App\Entities\Department($request->getPost());
			if($department->save($item)){
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
			$response["errors"] = $department->errors();
		}

		echo json_encode($response);
	}
}

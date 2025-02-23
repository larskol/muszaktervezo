<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Shift;

class Shifts extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "shifts";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "shifts";
	}
	
	/**
	 * Adatbázisban szerepló ismeretek listája
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleShifts'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleShifts"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$shift = new Shift();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderBusinessHoursStart"), lang("Table.tableHeaderBusinessHoursEnd"), lang("Table.tableHeaderCreatedAt")]);
        $items = $shift->select("'' AS dtDummy, id, name, start_time, end_time, created_at")->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 5;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Új ismeret hozzáadása
	 */
	public function create(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddShift'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddShift"),
			//"subtitle" => lang("Site.siteTitleAddShift"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$department = new \App\Models\Department();
		$formContent["departments"] = $department->findAll();
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Ismeret szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditShift'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditShift"),
			//"subtitle" => lang("Site.siteTitleEditShift"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];

		$shift = new Shift();
		$formContent["item"] = $shift->find($id);
		$department = new \App\Models\Department();
		$formContent["departments"] = $department->findAll();
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * Ismeret(ek) törlése
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
			$shift = new Shift();
			try{
				if($shift->delete($filteredItems)){
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
			}catch(\Exception $e){
				$messageContent = [
					"type" => "warning",
					"message" => lang("Messages.messageCantBeDeleted")
				];
				$response["message"] = view("message", $messageContent);
			}
		}
		
		echo json_encode($response);
	}

	/**
	 * (Ajax hívás) Ismeret mentése.
	 * Ha nincs megadva ID, akkor új ismeret hozzáadása,
	 * ha van megadva ID, akkor az adott ID-vel rendelkező ismeret szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$shift = new Shift();
		if($shift->validate($request->getPost())){
			$item = new \App\Entities\Shift($request->getPost());
			$item->departments = $request->getPost("departments");
			//var_dump($item);exit;
			if($shift->save($item)){
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
			$response["errors"] = $shift->errors();
		}

		echo json_encode($response);
	}
}

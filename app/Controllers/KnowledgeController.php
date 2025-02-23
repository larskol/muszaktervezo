<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Knowledge;
use Exception;

class KnowledgeController extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "knowledge";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "knowledge";
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleKnowledge'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleKnowledge"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$knowledge = new Knowledge();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderCreatedAt")]);
        $items = $knowledge->select("'' AS dtDummy, id, name, created_at")->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 3;
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddKnowledge'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddKnowledge"),
			//"subtitle" => lang("Site.siteTitleAddKnowledge"),
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditKnowledge'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditKnowledge"),
			//"subtitle" => lang("Site.siteTitleEditKnowledge"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];

		$knowledge = new Knowledge();
		$formContent["item"] = $knowledge->find($id);
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
			$knowledge = new Knowledge();
			try{
				if($knowledge->delete($filteredItems)){
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
		$knowledge = new Knowledge();
		if($knowledge->validate($request->getPost())){
			$item = new \App\Entities\Knowledge($request->getPost());
			$item->departments = $request->getPost("departments");
			if($knowledge->save($item)){
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
			$response["errors"] = $knowledge->errors();
		}

		echo json_encode($response);
	}
}

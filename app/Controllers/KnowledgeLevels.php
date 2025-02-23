<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KnowledgeLevel;

class KnowledgeLevels extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "knowledge-levels";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "knowledge_level";
	}
	/**
	 * Adatbázisban szerepló tudásszintek listája
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
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleKnowledgeLevels'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleKnowledgeLevels"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "/{$this->controllerUrl}/{$this->deleteUrl}",
		];

		$knowledgeLevel = new KnowledgeLevel();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderExperienceYear"), lang("Table.tableHeaderCreatedAt")]);
        $items = $knowledgeLevel->select("'' AS dtDummy, id, name, experience, created_at")->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 3;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Új tudásszint hozzáadása
	 */
	public function create(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleAddKnowledgeLevel'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleAddKnowledgeLevel"),
			//"subtitle" => lang("Site.siteTitleAddKnowledgeLevel"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Tudásszint szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditKnowledgeLevel'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditKnowledgeLevel"),
			//"subtitle" => lang("Site.siteTitleEditKnowledgeLevel"),
			"backButtonUrl" => "/{$this->controllerUrl}",
		];

		$knowledgeLevel = new KnowledgeLevel();
		$formContent["item"] = $knowledgeLevel->find($id);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * Tudásszint(ek) törlése
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
			$knowledgeLevel = new KnowledgeLevel();
			try{
				if($knowledgeLevel->delete($filteredItems)){
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
	 * (Ajax hívás) Tudásszint mentése.
	 * Ha nincs megadva ID, akkor új tudásszint hozzáadása,
	 * ha van megadva ID, akkor az adott ID-vel rendelkező tudásszint szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$knowledgeLevel = new KnowledgeLevel();
		if($knowledgeLevel->validate($request->getPost())){
			$item = new \App\Entities\KnowledgeLevel($request->getPost());
			if($knowledgeLevel->save($item)){
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
			$response["errors"] = $knowledgeLevel->errors();
		}

		echo json_encode($response);
	}
}

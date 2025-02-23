<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Restriction;

class Restrictions extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "restrictions";
		$this->listUrl = "list";
		$this->createUrl = "create";
		$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "restrictions";
	}
	/**
	 * Adatbázisban szerepló korlátozások listája
	 */
	public function index()
	{
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleRestrictions'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleRestrictions"),
			"subtitle" => "",
			"editUrl" => "/{$this->controllerUrl}/{$this->editUrl}/",
			"deleteUrl" => "",
			"showEditButton" => true,
			"showDeleteButton" => false
		];

		$restriction = new Restriction();
		$tableConfig = config("DataTable");
		$tableSettings = $tableConfig->dataTableOpen;
		$table = new \CodeIgniter\View\Table($tableSettings);
        $table->setHeading(["", lang("Table.tableHeaderID"), lang("Table.tableHeaderName"), lang("Table.tableHeaderCost"), lang("Table.tableHeaderCreatedAt")]);
        $items = $restriction->select("'' AS dtDummy, id, name, cost, created_at")->asArray()->findAll();
        $formContent["table"] = $table->generate($items);
        $formContent["tableOrderColumn"] = 4;
        $formContent["tableOrderDirection"] = "desc";
		$pageContent["content"] = view("list", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Korlátozás szerkesztése
	 */
	public function edit($id){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->editUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleEditRestriction'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleEditRestriction"),
			"subtitle" => "",
			"backButtonUrl" => "/{$this->controllerUrl}",
		];

		$restriction = new Restriction();
		$formContent["item"] = $restriction->find($id);
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	/**
	 * (Ajax hívás) Korlátozás mentése.
	 * Az adott ID-vel rendelkező korlátozás szerkesztése.
	 */
	public function save(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$restriction = new Restriction();
		if($restriction->validate($request->getPost())){
			$item = new \App\Entities\Restriction($request->getPost());
			$id = $request->getPost("id");
			if($id && is_numeric($id)){
				if($restriction->update($id, $item)){
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
				$response["status"] = false;
				$messageContent = [
					"type" => "danger",
					"message" => lang("Messages.messageWrongData")
				];
				$response["message"] = view("message", $messageContent);
			}
		}else{
			$response["errors"] = $restriction->errors();
		}

		echo json_encode($response);
	}
}

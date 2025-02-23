<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Skill;

class Skills extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "skills";
		$this->listUrl = "list";
		//$this->createUrl = "create";
		//$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "skills";
	}

	/**
	 * A felhasználó által hozzáadott saját ismereteinek és azok szintjeinek listája
	 */
	public function index()
	{
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleMySkills'),
			"userLoginInfo" => get_user_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleMySkills"),
			"subtitle" => "",
			"backButtonUrl" => null
		];


		$knowledge = new \App\Models\Knowledge();	
		$skill = new Skill();

		$knowledgeData = $knowledge->getAvailableKnowledge($session->get("userDepartment"));
		$userSkills = $skill->getUserSkills($session->get("userId"));

		$userKnowledge = array_filter($knowledgeData, function ($a) use($userSkills){
			foreach ($userSkills as $key => $value) {
				if($a->id == $value->knowledge_id) return true;
			}
			return false;
		});

		$knowledgeData = array_map(function ($a) use($userSkills){
			$a->available = true;
			foreach ($userSkills as $key => $value) {
				if($a->id == $value->knowledge_id){
					$a->available = false;
					$a->value = $value->knowledge_level_id;
					break;
				}
			}
			return $a;
		}, $knowledgeData);

		$formContent["knowledge"] = $knowledgeData;
		$formContent["items"] = $userKnowledge;

		$knowledgeLevel = new \App\Models\KnowledgeLevel();
		$formContent["knowledgeLevels"] = $knowledgeLevel->findAll();
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	public function showItem($viewContent = null){
		return view("{$this->viewDir}/skill_item", $viewContent);
	}

	public function save(){
		$session = session();
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$insert = $request->getPost("insert");

		if(isset($insert)){
			$validationRules = [
				"knowledge" => [
					"label" => "Form.formKnowledge",
					"rules" => "required|is_natural_no_zero|is_allowed_item_user[knowledge]|is_not_unique[knowledge.id,id,{knowledge}]|skill_not_exists[{$session->get("userId")}]"
				],
				"level" => [
					"label" => "Form.formLevel",
					"rules" => "required|is_natural_no_zero|is_not_unique[knowledge_levels.id,id,{level}]"
				]
			];

			$skill = new Skill();
			if($this->validate($validationRules)){
				$item = new \App\Entities\Skill();
				$item->user_id = $session->get("userId");
				$item->knowledge_id = $request->getPost("knowledge");
				$item->knowledge_level_id = $request->getPost("level");
				if($skill->insert($item) !== false){
					$response["status"] = true;
					$response["level"] = $item->knowledge_level_id;
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
				$messageContent = [
					"type" => "danger",
					"errors" => $this->validator->getErrors()
				];
				$response["errors"] = view("message", $messageContent);
			}
		}else{
			$validationRules = [
				"knowledge" => [
					"label" => "Form.formKnowledge",
					"rules" => "required|is_natural_no_zero|is_not_unique[knowledge.id,id,{knowledge}]"
				],
				"level" => [
					"label" => "Form.formLevel",
					"rules" => "required|is_natural_no_zero|is_not_unique[knowledge_levels.id,id,{level}]"
				]
			];

			$skill = new Skill();
			if($this->validate($validationRules)){
				$item = new \App\Entities\Skill();
				$item->user_id = $session->get("userId");
				$item->knowledge_id = $request->getPost("knowledge");
				$item->knowledge_level_id = $request->getPost("level");
				if($skill->where(['user_id' => $session->get("userId"), "knowledge_id" => $item->knowledge_id])->update(null, $item)){
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
				$messageContent = [
					"type" => "danger",
					"errors" => $this->validator->getErrors()
				];
				$response["errors"] = view("message", $messageContent);
			}
		}

		echo json_encode($response);
	}

	public function delete(){
		$session = session();
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$validationRules = [
			"knowledge" => [
				"label" => "Form.formKnowledge",
				"rules" => "required|is_natural_no_zero"
			]
		];
		$skill = new Skill();
		if($this->validate($validationRules)){
			$item = new \App\Entities\Skill();
			$item->user_id = $session->get("userId");
			$item->knowledge_id = $request->getPost("knowledge");
			if($skill->where(["user_id" => $item->user_id, "knowledge_id" => $item->knowledge_id])->delete(null)){
				$response["status"] = true;
				$response["itemId"] = $item->knowledge_id;
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
				"message" => lang("Messages.messageDefault")
			];
			$response["message"] = view("message", $messageContent);
		}

		echo json_encode($response);	
	}
}

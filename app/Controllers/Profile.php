<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class Profile extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "profile";
		$this->viewDir = "profile";
	}

	/**
	 * Saját profil megtekintése
	 */
	public function index(){
		$session = session();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => $this->createUrl,
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleProfile'),
			"userLoginInfo" => get_admin_login_info_text()
		];
		$formContent = [
			"title" => lang("Site.siteTitleProfile"),
		];
		$user = new User();
		$formContent["item"] = $user->find($session->get("userId"));
		$roles = new \App\Libraries\RoleLib();
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * (Ajax hívás) Mentés.
	 * A felhasználó módosíthatja a jelszavát
	 */
	public function save(){
		$session = session();
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$validationRules = [
			"current_password" => [
				"label" => "Form.formCurrentPassword",
				"rules" => "required"
			],
			"password" => [
				"label" => "Form.formPassword",
				"rules" => "required|min_length[6]"
			],
			"password_confirm" => [
				"label" => "Form.formPasswordConfirm",
				"rules" => "matches[password]"
			]
		];

		if($this->validate($validationRules)){
			$user = new User();			
			$userData = $user->find($session->get("userId"));
			if(password_verify($request->getPost("current_password"), $userData->password)){
				$item = new \App\Entities\User();
				$item->id = $session->get("userId");
				$item->password = $request->getPost("password");
				if($user->save($item)){
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
			$response["errors"] = $this->validator->getErrors();
		}

		echo json_encode($response);
	}
}

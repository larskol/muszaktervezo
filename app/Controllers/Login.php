<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "login";
	}

	/**
	 * Bejelentkezési képernyő
	 */
	public function index()
	{
		$session = session();
		//ha már be van jelentkezve, átirányítjuk
		if($session->has("userRole")){
			if($session->get("userRole") == "admin"){
				return redirect()->to(site_url("users"));
			}elseif($session->get("userRole") == "department_admin"){
				return redirect()->to(site_url("department-users"));
			}else{
				return redirect()->to(site_url("skills"));
			}
		}

		$pageContent = [
			"route" => false,
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleSignIn')
		];
		$formContent = [
			"title" => lang("Site.siteTitleSignIn"),
			"subtitle" => "",
		];

		$pageContent["content"] = view("login", $formContent);
		echo view("layout", $pageContent);
	}

	/**
	 * Bejelentkezési adatok ellenőrzése
	 */
	public function auth(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');

		$validationRules = [
			'email' => 'required|valid_email|is_not_unique[users.email]',
			'password' => 'required'
		];
		if($this->validate($validationRules)){
			$model = new \App\Models\User();
			$email = $request->getPost('email');
			$password = $request->getPost('password');
			$user = $model->select("id, email, password, firstname, lastname, role, department_id, departments")->where("email", $email)->get()->getRow(0, $model->returnType);
			if($user){
				if(password_verify($password, $user->password)){
					$response["status"] = true;
					$session = session();
					$sessionData = [
						'userId' => $user->id,
						'userRole' => $user->role,
						'userDepartment' => $user->department_id,
						'userFirstname' => $user->firstname,
						'userLastname' => $user->lastname,
						'userName' => $user->lastname." ".$user->firstname
					];
					$department = new \App\Models\Department();
					$userDepartment = $department->where("id", $user->department_id)->get()->getRow(0, $department->returnType);
					if($userDepartment){
						$sessionData["userDepartmentName"] = $userDepartment->name;
					}
					if($user->role == "admin"){
						$response["redirectUrl"] = site_url("users");
					}elseif($user->role == "department_admin"){
						$response["redirectUrl"] = site_url("department-users");
						$sessionData["userDepartments"] = $user->departments;
						//hozzáadáskor kötelező megadni, így mindig van 0. elem
						if(isset($user->departments[0])){
							$sessionData["userCurrentDepartmentId"] = $user->departments[0];
						}else{
							$sessionData["userCurrentDepartmentId"] = -1;
						}
					}else{
						$response["redirectUrl"] = site_url("skills");
					}
					$session->set($sessionData);
				}else{
					$response["status"] = false;
					$messageContent = [
						"type" => "danger",
						"message" => lang("Messages.messageWrongData")
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
			$response["status"] = false;
			$messageContent = [
				"type" => "danger",
				"message" => lang("Messages.messageWrongData")
			];
			$response["message"] = view("message", $messageContent);
		}

		echo json_encode($response);
	}

	/**
	 * Kijelentkezés és visszairányítás a login képernyőre
	 */
	public function logout(){
		$session = session();
		$session->destroy();
		return redirect()->to(site_url("login"));
	}
}

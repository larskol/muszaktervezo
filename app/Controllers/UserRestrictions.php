<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserRestriction;

class UserRestrictions extends BaseController
{
	public function __construct()
	{
		$this->controllerUrl = "my-restrictions";
		$this->listUrl = "list";
		//$this->createUrl = "create";
		//$this->editUrl = "edit";
		$this->deleteUrl = "delete";
		$this->viewDir = "user_restrictions";
		helper("user_restrictions");
	}

	/**
	 * A felhasználó által hozzáadott következő havi korlátozásainak listája
	 */
	public function index()
	{
		$session = session();
		$userRestriction = new UserRestriction();
		$user = new \App\Models\User();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "current",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleMyRestrictions'),
			"userLoginInfo" => get_user_login_info_text()
		];

		$userAllPoints = $user->getBonusPoints($session->get("userId"));
		$userSpentPoints = $userRestriction->getUserSpentPoint($session->get("userId"));
		$userAvailablePoints = $userAllPoints - $userSpentPoints;

		$formContent = [
			"title" => lang("Site.siteTitleMyRestrictions"). " - ".lang("Site.siteMonth", [(new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m")]),
			"subtitle" => "",
			"backButtonUrl" => null,
			"spentPoints" => $userSpentPoints,
			"availablePoints" => $userAvailablePoints
		];
	
		$items = $userRestriction->getUserRestrictionsOneMonth($session->get("userId"), true);
		$restriction = new \App\Models\Restriction();
		$formContent["addedItems"] = $items;
		$formContent["restrictions"] = $restriction->findAll();
		$pageContent["content"] = view("{$this->viewDir}/form", $formContent);
		echo view("layout", $pageContent);	
	}

	public function showItem($viewContent = null){
		return view("{$this->viewDir}/added", $viewContent);
	}

	public function getInput(){
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$validationRules = [
			"restriction_type" => [
				"label" => "Form.formRestriction",
				"rules" => "required|is_natural_no_zero|is_not_unique[restrictions.id,id,{restriction_type}]"
			]
		];

		if($this->validate($validationRules)){
			$session = session();
			$response["status"] = true;
			$restriction = new \App\Models\Restriction();
			$id = $request->getPost("restriction_type");
			$item = $restriction->find($id);
			//alapból nem jelenik meg a dátum választó
			$optionsViewData = false;
			//mindhárom opció elérhetó alapból
			$availableOptions = [true, true, true];
			switch ($item->input_type) {
				case 'input':
					switch ($item->id) {
						//heti munkanapok száma
						case 3:
							$viewData = [
								"placeholder" => $item->name,
								"type" => "number",
								"step" => 0.5,
								"min" => 0,
								"max" => 7,
							];
							$optionsViewData = true;
							$availableOptions = [true, false, true];
							break;
						//heti egymás utáni munkanapok száma
						case 4:
							$viewData = [
								"placeholder" => $item->name,
								"type" => "number",
								"step" => 0.5,
								"min" => 1,
								"max" => 7,
							];
							$optionsViewData = true;
							$availableOptions = [true, false, true];
							break;
						//heti egymás utáni szabadnapok száma
						case 5:
							$viewData = [
								"placeholder" => $item->name,
								"type" => "number",
								"step" => 0.5,
								"min" => 0,
								"min" => 1,
								"max" => 7,
							];
							$optionsViewData = true;
							$availableOptions = [true, false, true];
							break;
						default:
							$viewData = [
								"placeholder" => $item->name,
							];
							$optionsViewData = false;
							break;
					}

					$response["view"] = view("{$this->viewDir}/templates/input", $viewData);
					break;
				case 'input_date':
					$viewData = [
						"placeholder" => lang("Form.formPlaceholderDate"),
						"type" => "date"
					];
					$response["view"] = view("{$this->viewDir}/templates/input", $viewData);
					break;
				case 'custom_days':
					$viewData = [
						"placeholder" => lang("Form.formPlaceholderDate"),
						"type" => "date"
					];
					$response["view"] = view("{$this->viewDir}/templates/custom_days", $viewData);
					$optionsViewData = true;
					//az adott nap itt nem lehet
					$availableOptions = [true, false, true];
					break;
				case 'shifts':
					$shift = new \App\Models\Shift();
					$viewData = [
						"data" => $shift->getAvailableShifts($session->get("userDepartment")),
					];
					$response["view"] = view("{$this->viewDir}/templates/shifts", $viewData);
					$optionsViewData = true;
					break;
				case 'users':
					$user = new \App\Models\User();
					$viewData = [
						"data" => $user->where("id !=", $session->get("userId"))->where("department_id", $session->get("userDepartment"))->findAll(),
					];
					$response["view"] = view("{$this->viewDir}/templates/users", $viewData);
					break;
			}
			$viewData = [
				"min" => $item->cost,
				"max" => "",
			];
			$response['costView'] = view("{$this->viewDir}/templates/cost", $viewData);
			if($optionsViewData){
				$config = config("App");
				$optionsContent = [
					"options" => $config->restrictionOptionTypes,
					"available" => $availableOptions
				];
				$response["optionsView"] = view("{$this->viewDir}/templates/options", $optionsContent);
			}
		}else{
			$messageContent = [
				"type" => "danger",
				"errors" => $this->validator->getErrors()
			];
			$response["errors"] = view("message", $messageContent);
		}

		echo json_encode($response);
	}

	public function save(){
		$session = session();
		$response = [
			"status" => false,
			"message" => "",
		];
		$request = service('request');
		$restrictionModel = new \App\Models\Restriction();
		
		$minPoint = 0;
		if(is_numeric($request->getPost("restriction_type"))){
			$restriction = $restrictionModel->find($request->getPost("restriction_type"));
			if($restriction){
				$minPoint = $restriction->cost;
			}
		}
		$user = new \App\Models\User();
		$userRestriction = new UserRestriction();
		$userAvailablePoints = $user->getBonusPoints($session->get("userId")) - $userRestriction->getUserSpentPoint($session->get("userId"));
		$validationRules = [
			"restriction_type" => [
				"label" => "Form.formRestriction",
				"rules" => "required|is_natural_no_zero|is_not_unique[restrictions.id,id,{restriction_type}]"
			],
			"restriction_value" => [
				"label" => "Form.formValue",
				"rules" => "required"
			],
			"restriction_cost" => [
				"label" => "Form.formCost",
				"rules" => "required|is_natural|greater_than_equal_to[{$minPoint}]|less_than_equal_to[{$userAvailablePoints}]"
			],
			"restriction_date_type" => [
				"label" => "Form.formDateInterval",
				"rules" => "permit_empty|in_list[1,2,3]"
			]
		];
		$errorMessages = [
			"restriction_cost" => [
				"less_than_equal_to" => lang("Messages.messageNotEnoughPoints")
			]
		];
		if($this->validate($validationRules, $errorMessages)){			
			$restriction = $restrictionModel->find($request->getPost("restriction_type"));
			//a megadott érték validálása
			$restrictionRules = [
				"restriction_value" => [
					"label" => "Form.formValue",
					"rules" => "required"
				]
			];
			$restrictionDateType = $request->getPost("restriction_date_type");
			switch ($restriction->input_type) {
				case 'input':
					switch ($restriction->id) {
						//heti munkanapok száma
						case 3:
							$restrictionRules = [
								"restriction_value" => [
									"label" => "Form.formValue",
									"rules" => "required|numeric|greater_than_equal_to[0]|less_than_equal_to[7]"
								],
								"restriction_date_type" => [
									"label" => "Form.formDateInterval",
									"rules" => "required|in_list[1,3]"
								]
							];
							if($restrictionDateType === "3"){
								$restrictionRules["restriction_date"] = [
									"label" => "Form.formWhen",
									"rules" => "required|valid_date[Y-m-d]|next_month_date"
								];
							}
							break;
						//heti egymás utáni munkanapok száma
						case 4:
							$restrictionRules = [
								"restriction_value" => [
									"label" => "Form.formValue",
									"rules" => "required|numeric|greater_than_equal_to[0]|less_than_equal_to[7]"
								],
								"restriction_date_type" => [
									"label" => "Form.formDateInterval",
									"rules" => "required|in_list[1,3]"
								]
							];
							if($restrictionDateType === "3"){
								$restrictionRules["restriction_date"] = [
									"label" => "Form.formWhen",
									"rules" => "required|valid_date[Y-m-d]|next_month_date"
								];
							}
							break;
						//heti egymás utáni szabadnapok száma
						case 5:
							$restrictionRules = [
								"restriction_value" => [
									"label" => "Form.formValue",
									"rules" => "required|numeric|greater_than_equal_to[0]|less_than_equal_to[7]"
								],
								"restriction_date_type" => [
									"label" => "Form.formDateInterval",
									"rules" => "required|in_list[1,3]"
								]
							];
							if($restrictionDateType === "3"){
								$restrictionRules["restriction_date"] = [
									"label" => "Form.formWhen",
									"rules" => "required|valid_date[Y-m-d]|next_month_date"
								];
							}
							break;
					}
					break;
				case 'input_date':
					$restrictionRules = [
						"restriction_value" => [
							"label" => "Form.formWhen",
							"rules" => "required|valid_date[Y-m-d]|next_month_date"
						]
					];
					//adott nap lesz a típus
					$restrictionDateType = 2;
					break;
				case 'custom_days':
					$restrictionRules = [
						"restriction_value" => [
							"label" => "Form.formValue",
							"rules" =>  "required|in_list[1,2,3,4,5,6,7]"
						],
						"restriction_date_type" => [
							"label" => "Form.formDateInterval",
							"rules" => "required|in_list[1,3]"
						]
					];
					if($restrictionDateType === "3"){
						$restrictionRules["restriction_date"] = [
							"label" => "Form.formWhen",
							"rules" => "required|valid_date[Y-m-d]|next_month_date"
						];
					}
					break;
				case 'shifts':
					$shift = new \App\Models\Shift();
					$data = $shift->getAvailableShifts($session->get("userDepartment"));
					$str = implode(",", array_column($data, "id"));
					$restrictionRules = [
						"restriction_value" => [
							"label" => "Form.formValue",
							"rules" => "required|in_list[{$str}]"
						],
						"restriction_date_type" => [
							"label" => "Form.formDateInterval",
							"rules" => "required|in_list[1,2,3]"
						]
					];
					if($restrictionDateType === "2" || $restrictionDateType === "3"){
						$restrictionRules["restriction_date"] = [
							"label" => "Form.formWhen",
							"rules" => "required|valid_date[Y-m-d]|next_month_date"
						];
					}
					break;
				case 'users':
					$data = $user->where("id !=", $session->get("userId"))->where("department_id", $session->get("userDepartment"))->findAll();
					$str = implode(",", array_column($data, "id"));
					$restrictionRules = [
						"restriction_value" => [
							"label" => "Form.formValue",
							"rules" => "required|in_list[{$str}]"
						]
					];
					//egész hónap lesz a típus
					$restrictionDateType = 1;
					break;
				default:
					//ide nem juthat el jelenleg, de továbbfejlesztés esetén kellhet
					$restrictionRules = [
						"restriction_value" => [
							"label" => "Form.formValue",
							"rules" => "required"
						]
					];
			}
			if($this->validate($restrictionRules)){
				$item = new \App\Entities\UserRestriction();
				$item->user_id = $session->get("userId");
				$item->restriction_id = $restriction->id;
				$item->type = $restriction->input_type;
				$item->value = $request->getPost("restriction_value");
				$item->bonus_point = $request->getPost("restriction_cost");
				$config = config("App");
				$item->value_type = $config->restrictionOptionTypes[$restrictionDateType-1];
				//ha van megadva dátum típus, be kell állítani hogy melyik mezőbe írja
				if(!is_null($request->getPost("restriction_date_type"))){
					if($item->value_type === "given_day"){
						$item->day_value = $request->getPost("restriction_date");
					}elseif($item->value_type === "given_week"){
						$item->week_value = $request->getPost("restriction_date");
						$item->week_number = (new \DateTime($request->getPost("restriction_date")))->format("W");
					}
				}else{
					if($item->value_type === "given_day"){
						$item->day_value = $request->getPost("restriction_value");
					}elseif($item->value_type === "given_week"){
						$item->week_value = $request->getPost("restriction_value");
					}
				}
				$id = $userRestriction->insert($item);
				if($id !== false){
					$response["status"] = true;
					$messageContent = [
						"type" => "success",
						"message" => lang("Messages.messageSaveSuccess")
					];
					$response["message"] = view("message", $messageContent);

					$added = $userRestriction->getItem($id);
					$viewContent = get_user_selected_restriction_item_data($added);
					$response["view"] = view("{$this->viewDir}/added", $viewContent);
					//a felhasználó költött és elérhető pontjai
					$userAllPoints = $user->getBonusPoints($session->get("userId"));
					$userSpentPoints = $userRestriction->getUserSpentPoint($session->get("userId"));
					$response["spentPoints"] = $userSpentPoints;
					$response["availablePoints"] = $userAllPoints - $userSpentPoints;
				}
			}else{
				$messageContent = [
					"type" => "danger",
					"errors" => $this->validator->getErrors()
				];
				$response["errors"] = view("message", $messageContent);
			}
		}else{
			$messageContent = [
				"type" => "danger",
				"errors" => $this->validator->getErrors()
			];
			$response["errors"] = view("message", $messageContent);
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
		if(is_numeric($request->getJsonVar("id"))){
			$userRestriction = new UserRestriction();
			$item = new \App\Entities\UserRestriction();
			$item->user_id = $session->get("userId");
			$item->id = $request->getJsonVar("id");
			if($userRestriction->where(["user_id" => $item->user_id, "id" => $item->id])->delete(null)){
				$response["status"] = true;
				$messageContent = [
					"type" => "success",
					"message" => lang("Messages.messageDeleteSuccess")
				];
				$response["message"] = view("message", $messageContent);
				$userAllPoints = (new \App\Models\User())->getBonusPoints($session->get("userId"));
				$userSpentPoints = $userRestriction->getUserSpentPoint($session->get("userId"));
				$response["spentPoints"] = $userSpentPoints;
				$response["availablePoints"] = $userAllPoints - $userSpentPoints;
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

	/**
	 * A felhasználó által korábban hozzáadott korlátozásainak listája
	 */
	public function previousRestrictions()
	{
		$session = session();
		$userRestriction = new UserRestriction();
		$user = new \App\Models\User();
		$pageContent = [
			"route" => [
				"userRole" => $session->get("userRole"),
				"main" => $this->controllerUrl,
				"sub" => "history",
			],
			"siteTitle" => lang('Site.sitePageTitle')." - ".lang('Site.siteTitleMyRestrictionsHistory'),
			"userLoginInfo" => get_user_login_info_text()
		];

		$request = service("request");
		$validation = service("validation");
		$selectedMonth = date("Y-m");
		if($validation->check($request->getGet("month"), "valid_date[Y-m]")){
			$selectedMonth = $request->getGet("month");
		}
		$userAllPoints = $user->getBonusPoints($session->get("userId"));
		$userSpentPoints = $userRestriction->getUserSpentPoint($session->get("userId"), $selectedMonth);
		$userAvailablePoints = $userAllPoints - $userSpentPoints;

		$formContent = [
			"title" => lang("Site.siteTitleMyRestrictionsHistory"),
			"subtitle" => "",
			"backButtonUrl" => null,
			"spentPoints" => $userSpentPoints,
			"availablePoints" => $userAvailablePoints
		];
	
		$items = $userRestriction->getUserRestrictionsOneMonth($session->get("userId"), true, $selectedMonth);
		$restriction = new \App\Models\Restriction();
		$formContent["addedItems"] = $items;
		$formContent["restrictions"] = $restriction->findAll();
		$formContent["dates"] = $userRestriction->getDates($session->get("userId"));
		$formContent["selectedMonth"] = $selectedMonth;
		$pageContent["content"] = view("{$this->viewDir}/history", $formContent);
		echo view("layout", $pageContent);	
	}

	public function showHistoryItem($viewContent = null){
		return view("{$this->viewDir}/history_items", $viewContent);
	}
}

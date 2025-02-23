<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Errors extends BaseController
{
	/**
	 * 403-as hibaüzenet megjelenítése (forbidden)
	 */
	public function forbidden(){
		$pageContent["content"] = view("errors/error_403");
		echo view("layout", $pageContent);
	}

	/**
	 * 404-es hibaüzenet megjelenítése (page not found)
	 */
	public function notFound(){
		$pageContent["content"] = view("errors/error_404");
		echo view("layout", $pageContent);		
	}
}
<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Shift extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function setDepartments(?array $departments)
    {
		if(!is_null($departments)){
        	$this->attributes['departments'] = json_encode($departments);
		}else{
			$this->attributes['departments'] = null;
		}

        return $this;
    }

	public function getDepartments()
    {
		//in_array() függvény miatt, ha null akkor üres tömb lesz
		if(!is_null($this->attributes['departments'])){
        	return json_decode($this->attributes['departments']);
		}
		return [];
    }
}

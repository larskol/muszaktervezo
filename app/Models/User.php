<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'users';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\User';
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['email', 'password', 'firstname', 'lastname', 'bonus_point', 'role', 'department_id', 'weekly_work_hours', 'paid_annual_leave', 'departments'];
	
	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = true;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	/**
	 * Visszaadja egy felhasználó pontjait
	 * 
	 * @param $id - felhasználó azonosítója
	 * 
	 * @return int
	 */
	public function getBonusPoints($id){
		return $this->select("bonus_point")->find($id)->bonus_point;
	}

	/**
	 * Visszaadja a megadott részleghez tartozó felhasználókat
	 * 
	 * @param $departmentId - részleg azonosító
	 * 
	 * @return array
	 */
	public function getDepartmentUsers($departmentId){
		return $this->where("department_id", $departmentId)->get()->getResult($this->returnType);
	}

	/**
	 * A törölt felhasználók adatait átírja
	 * 
	 * @param $idArray - a azonosítókat tartalmazó tömb
	 * 
	 * @return bool
	 */
	public function removeUserData($idArray){
		$data = [];
		foreach($idArray as $id){
			$data[] = [
				"id" => $id,
				"email" => lang("Site.siteRemovedItem")."-{$id}",
				"firstname" => lang("Site.siteRemovedItem"),
				"lastname" => lang("Site.siteRemovedItem")
			];
		}
		return $this->updateBatch($data, "id");
	}
}

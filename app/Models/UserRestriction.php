<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRestriction extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user_selected_restrictions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\UserRestriction';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ["user_id", "restriction_id", "value", "type", "bonus_point", "day_value", "week_value", "value_type", "week_number"];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	//protected $deletedField         = 'deleted_at';

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
	 * Visszaadja a felhasználó által hozzáadott korlátozásokat
	 * 
	 * @param $userId - felhasználó azonoosítója
	 * @param $withJoin - csak az adatokat, vagy joinnal együtt a többi tábla adata is
	 * 
	 * @return array
	 */
	public function getUserRestrictions($userId, $withJoin = false){
		if($withJoin){
			return $this->select("user_selected_restrictions.id AS id, user_selected_restrictions.user_id,
			user_selected_restrictions.type, user_selected_restrictions.value, user_selected_restrictions.bonus_point,
			user_selected_restrictions.restriction_id, user_selected_restrictions.day_value,
			user_selected_restrictions.week_value, user_selected_restrictions.week_number,
			user_selected_restrictions.value_type, restrictions.name")
			->where("user_selected_restrictions.user_id", $userId)
			->join("restrictions", "user_selected_restrictions.restriction_id=restrictions.id", "left outer")
			->findAll();
		}
		return $this->where("user_id", $userId)->findAll();
	}

	/**
	 * Visszaadja a felhasználó által hozzáadott korlátozásokat a megadott hónapra
	 * 
	 * @param $userId - felhasználó azonoosítója
	 * @param $withJoin - csak az adatokat, vagy joinnal együtt a többi tábla adata is
	 * @param $dateYm - év-hónap, melyik havi korlátozásokat adja vissza. Ha nincs megadva, akkor a jelenlegi hónap
	 * 
	 * @return array
	 */
	public function getUserRestrictionsOneMonth($userId, $withJoin = false, $dateYm = null){
		$date = new \DateTime($dateYm);
		$from = $date->format("Y-m")."-01 00:00:00";
		//hozzáadunk 1 hónapot
		$to = (new \DateTime($from))->add(new \DateInterval("P1M"))->format("Y-m-d H:i:s");
		if($withJoin){
			return $this->select("user_selected_restrictions.id AS id, user_selected_restrictions.user_id,
			user_selected_restrictions.type, user_selected_restrictions.value, user_selected_restrictions.bonus_point,
			user_selected_restrictions.restriction_id, user_selected_restrictions.day_value,
			user_selected_restrictions.week_value, user_selected_restrictions.week_number,
			user_selected_restrictions.value_type, restrictions.name")
			->where("user_selected_restrictions.user_id", $userId)
			->where(["user_selected_restrictions.created_at >=" => $from, "user_selected_restrictions.created_at <" => $to])
			->join("restrictions", "user_selected_restrictions.restriction_id=restrictions.id", "left outer")
			->findAll();
		}
		return $this->where("user_id", $userId)->where(["created_at >=" => $from, "created_at <" => $to])->findAll();
	}

	/**
	 * Visszaad egy korlátozást
	 * 
	 * @param $id - korlátozás azonosítója
	 * 
	 * @return mixed \Entities\UserRestriction objektum || üres tömb
	 */
	public function getItem($id){
		return $this->select("user_selected_restrictions.id AS id, user_selected_restrictions.user_id,
		user_selected_restrictions.type, user_selected_restrictions.value, user_selected_restrictions.bonus_point,
		user_selected_restrictions.restriction_id, user_selected_restrictions.day_value,
		user_selected_restrictions.week_value, user_selected_restrictions.week_number,
		user_selected_restrictions.value_type, restrictions.name")
		->where("user_selected_restrictions.id", $id)
		->join("restrictions", "user_selected_restrictions.restriction_id=restrictions.id", "left outer")
		->get()
		->getRow(0, $this->returnType);
	}

	/**
	 * Visszaadja hogy mennyi pontot költött el a felhasználó a megadottadott vagy jelenlegi hónapban
	 * 
	 * @param $userId - felhasználó atonosítója
	 * @param $dateYm - év-hónap, melyik havi pontokat adja vissza. Ha nincs megadva, akkor a jelenlegi hónap
	 * 
	 * @return int
	 */
	public function getUserSpentPoint($userId, $dateYm = null){
		$date = new \DateTime($dateYm);
		$from = $date->format("Y-m")."-01 00:00:00";
		//hozzáadunk 1 hónapot
		$to = (new \DateTime($from))->add(new \DateInterval("P1M"))->format("Y-m-d H:i:s");
		$points = $this->select("SUM(bonus_point) as points")
		->where(["user_id" => $userId, "created_at >=" => $from, "created_at <" => $to])->get()->getRow(0, $this->returnType)->points;

		if(is_null($points)){
			$points = 0;
		}

		return $points;
	}

	/**
	 * Visszaadja az eddig előforduló hónapokat év-hó formában
	 * 
	 * @param $userId - felhasználó azonosító
	 * @param $order - lekérdezési sorrend dátum alapján
	 * 
	 * @return array
	 */
	public function getDates($userId, $order = "desc"){
		return $this->distinct()->select('DATE_FORMAT(created_at, "%Y-%m") as date')->orderBy("date", $order)->where("user_id", $userId)->findAll();
	}
}

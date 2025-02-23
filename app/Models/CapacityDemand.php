<?php

namespace App\Models;

use CodeIgniter\Model;

class CapacityDemand extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'capacity_demand';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\CapacityDemand';
	protected $useSoftDeletes        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ["day", "department_id", "shift_id", "knowledge_id", "knowledge_level_id", "amount"];

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
	 * Visszaadja az eddig előforduló hónapokat év-hó formában
	 * 
	 * @param $departmentId - részleg azonosító
	 * @param $order - lekérdezési sorrend dátum alapján
	 * 
	 * @return array
	 */
	public function getDates($departmentId, $order = "desc"){
		return $this->distinct()->select('DATE_FORMAT(day, "%Y-%m") as date')->orderBy("date", $order)->where("department_id", $departmentId)->findAll();
	}

	/**
	 * Visszaadja az aktív részleghez hozzáadott igényekel egy hónapra
	 * 
	 * @param $departmentId - részleg azonoosítója
	 * @param $withJoin - csak az adatokat, vagy joinnal együtt a többi tábla adata is
	 * @param $dateYm - év-hónap, melyik havi korlátozásokat adja vissza. Ha nincs megadva, akkor a jelenlegi hónap
	 * 
	 * @return array
	 */
	public function getItemsOneMonth($departmentId, $withJoin = false, $dateYm = null){
		$date = new \DateTime($dateYm);
		$from = $date->format("Y-m-01");
		//hozzáadunk 1 hónapot
		$to = (new \DateTime($from))->add(new \DateInterval("P1M"))->format("Y-m-d");
		if($withJoin){
			return $this->select('capacity_demand.id, capacity_demand.day, capacity_demand.department_id AS cd_department_id,
			capacity_demand.shift_id AS cd_shift_id, capacity_demand.knowledge_id AS cd_knowledge_id, capacity_demand.knowledge_level_id AS cd_knowledge_level_id,
			departments.name AS department_name, shifts.name AS shift_name,
			CONCAT(TIME_FORMAT(shifts.start_time,"%H:%i"),"-",TIME_FORMAT(shifts.end_time,"%H:%i")) AS shift_time,
			shifts.start_time AS shift_start, shifts.end_time AS shift_end, knowledge.name AS knowledge_name,
			knowledge_levels.name AS knowledge_level_name, capacity_demand.amount')
			->where("capacity_demand.department_id", $departmentId)
			->where(["capacity_demand.day >=" => $from, "capacity_demand.day <" => $to])
			->join("departments", "capacity_demand.department_id=departments.id", "left outer")
			->join("shifts", "capacity_demand.shift_id=shifts.id", "left outer")
			->join("knowledge", "capacity_demand.knowledge_id=knowledge.id", "left outer")
			->join("knowledge_levels", "capacity_demand.knowledge_level_id=knowledge_levels.id", "left outer")
			->orderBy("capacity_demand.day", "asc")
			->findAll();
		}
		return $this->where("department_id", $departmentId)->where(["day >=" => $from, "day <" => $to])->orderBy("day", "asc")->findAll();
	}
}

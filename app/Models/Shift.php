<?php

namespace App\Models;

use CodeIgniter\Model;

class Shift extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'shifts';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\Shift';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['name', 'start_time', 'end_time', "departments"];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [
		'name' => [
			'label' => 'Form.formName',
			'rules' => 'required|max_length[100]|is_unique[shifts.name,id,{id}]'
		],
		'start_time' => [
			'label' => 'Form.formWorkStartTime',
			'rules' => 'required|valid_date[H:i]'
		],
		'end_time' => [
			'label' => 'Form.formWorkEndTime',
			'rules' => 'required|valid_date[H:i]'
		],
		'departments' => [
			'label' => 'Form.formDepartments',
			'rules' => 'allowed_department'
		]
	];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
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
	 * Visszaadja azokat a műszakokat, amelyek elérhetőek a megadott id-vel rendelkező részlegnél
	 * 
	 * @param int $departmentId - részleg azonosító
	 * 
	 * @return array - objektumokat tartalmazó tömb vagy üres tömb
	 */
	public function getAvailableShifts(?int $departmentId = null){
		if(!is_null($departmentId)){
			return $this->select("'' AS dtDummy, id, name, start_time, end_time, created_at")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->findAll();
		}

		return [];
	}
}

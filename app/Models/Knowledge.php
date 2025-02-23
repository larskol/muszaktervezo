<?php

namespace App\Models;

use CodeIgniter\Model;

class Knowledge extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'knowledge';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\Knowledge';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['name', 'departments'];

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
			'rules' => 'required|max_length[100]|is_unique[knowledge.name,id,{id}]'
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
	 * Visszaadja azokat az ismereteket, amelyek elérhetőek a megadott id-vel rendelkező részlegnél
	 * 
	 * @param int $departmentId - részleg azonosító
	 * 
	 * @return array - objektumokat tartalmazó tömb vagy üres tömb
	 */
	public function getAvailableKnowledge(?int $departmentId = null){
		if(!is_null($departmentId)){
			return $this->select("'' AS dtDummy, id, name, created_at")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->findAll();
		}

		return [];
	}
}

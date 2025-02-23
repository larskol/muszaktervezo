<?php

namespace App\Models;

use CodeIgniter\Model;

class Department extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'departments';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\Department';
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['name'];

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
			'rules' => 'required|max_length[100]|is_unique[departments.name,id,{id}]'
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
	 * Ellenőrzi, hogy üres-e az adott részleg
	 * 
	 * @param $departmentIds - részleg azonosító
	 * 
	 * @return bool
	 */
	public function isEmpty($departmentIds){
		return $this->db->table("users")->whereIn("department_id", $departmentIds)->countAllResults() == 0;
	}
}

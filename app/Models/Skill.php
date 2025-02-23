<?php

namespace App\Models;

use CodeIgniter\Model;

class Skill extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user_knowledge_levels';
	protected $returnType           = '\App\Entities\Skill';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ["user_id", "knowledge_id", "knowledge_level_id"];

	// Dates
	protected $useTimestamps        = false;
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

	public function getUserSkills($userId){
		return $this->where("user_id", $userId)->findAll();
	}
}

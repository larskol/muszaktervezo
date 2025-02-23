<?php

namespace App\Models;

use CodeIgniter\Model;

class KnowledgeLevel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'knowledge_levels';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\KnowledgeLevel';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['name', 'experience'];

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
			'rules' => 'required|max_length[100]|is_unique[knowledge_levels.name,id,{id}]'
		],
		'experience' => [
			'label' => 'Form.formExperienceYear',
			'rules' => 'required|is_natural'
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
}

<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Navigation extends BaseConfig
{
	public $items = [
		'home' => [
			'label' => 'navHome',
			'href' => '/',
			'icon' => '<i class="bi bi-house-door"></i>',
		],
		'departments' => [
			'role' => 'admin',
			'label' => 'navDepartments',
			'icon' => '<i class="bi bi-gear-wide-connected"></i>',
			'submenu' => [
				'create' => [
					'role' => 'admin',
					'label' => 'navAddNew',
					'href' => '/departments/create',
					'icon' => '<i class="bi bi-file-earmark-text"></i>',
				],
				'list' => [
					'role' => 'admin',
					'label' => 'navList',
					'href' => '/departments',
					'icon' => '<i class="bi bi-list-check"></i>',
				]
			]
		],
		'knowledge' => [
			'role' => 'admin',
			'label' => 'navKnowledge',
			'icon' => '<i class="bi bi-gear-wide-connected"></i>',
			'submenu' => [
				'create' => [
					'role' => 'admin',
					'label' => 'navAddNew',
					'href' => '/knowledge/create',
					'icon' => '<i class="bi bi-file-earmark-text"></i>',
				],
				'list' => [
					'role' => 'admin',
					'label' => 'navList',
					'href' => '/knowledge',
					'icon' => '<i class="bi bi-list"></i>',
				]
			]
		],
		'knowledge-levels' => [
			'role' => 'admin',
			'label' => 'navKnowledgeLevels',
			'icon' => '<i class="bi bi-gear-wide-connected"></i>',
			'submenu' => [
				'create' => [
					'role' => 'admin',
					'label' => 'navAddNew',
					'href' => '/knowledge-levels/create',
					'icon' => '<i class="bi bi-file-earmark-text"></i>',
				],
				'list' => [
					'role' => 'admin',
					'label' => 'navList',
					'href' => '/knowledge-levels',
					'icon' => '<i class="bi bi-list"></i>',
				]
			]
		],
		'shifts' => [
			'role' => 'admin',
			'label' => 'navShifts',
			'icon' => '<i class="bi bi-clock"></i>',
			'submenu' => [
				'create' => [
					'role' => 'admin',
					'label' => 'navAddNew',
					'href' => '/shifts/create',
					'icon' => '<i class="bi bi-file-earmark-text"></i>',
				],
				'list' => [
					'role' => 'admin',
					'label' => 'navList',
					'href' => '/shifts',
					'icon' => '<i class="bi bi-list"></i>',
				]
			]
		],
		'restrictions' => [
			'role' => 'admin',
			'label' => 'navRestrictions',
			'icon' => '<i class="bi bi-filter-square"></i>',
			'href' => '/restrictions',
		],
		'users' => [
			'role' => 'admin',
			'label' => 'navUsers',
			'icon' => '<i class="bi bi-people"></i>',
			'submenu' => [
				'create' => [
					'role' => 'admin',
					'label' => 'navAddNew',
					'href' => '/users/create',
					'icon' => '<i class="bi bi-person-plus"></i>',
				],
				'upload' => [
					'role' => 'admin',
					'label' => 'navAddNewUserFromFile',
					'href' => '/users/upload',
					'icon' => '<i class="bi bi-upload"></i>'
				],
				'update-points' => [
					'role' => 'admin',
					'label' => 'navUpdateUserBonusPoints',
					'href' => '/users/update-points',
					'icon' => '<i class="bi bi-upload"></i>'
				],
				'list' => [
					'role' => 'admin',
					'label' => 'navList',
					'href' => '/users',
					'icon' => '<i class="bi bi-person-lines-fill"></i>',
				]
			]
		],
		'admin-schedule' => [
			'role' => 'admin',
			'label' => 'navSchedule',
			'icon' => '<i class="bi bi-calendar-week"></i>',
			'href' => '/schedule/admin'
		],
		'department-users' => [
			'role' => 'department_admin',
			'label' => 'navUsers',
			'icon' => '<i class="bi bi-people"></i>',
			'submenu' => [
				'create' => [
					'role' => 'department_admin',
					'label' => 'navAddNew',
					'href' => '/department-users/create',
					'icon' => '<i class="bi bi-person-plus"></i>',
				],
				'upload' => [
					'role' => 'department_admin',
					'label' => 'navAddNewUserFromFile',
					'href' => '/department-users/upload',
					'icon' => '<i class="bi bi-upload"></i>'
				],
				'update-points' => [
					'role' => 'department_admin',
					'label' => 'navUpdateUserBonusPoints',
					'href' => '/department-users/update-points',
					'icon' => '<i class="bi bi-upload"></i>'
				],
				'list' => [
					'role' => 'department_admin',
					'label' => 'navList',
					'href' => '/department-users',
					'icon' => '<i class="bi bi-person-lines-fill"></i>',
				]
			]
		],
		'capacity-demands' => [
			'role' => 'department_admin',
			'label' => 'navCapacityDemands',
			'icon' => '<i class="bi bi-calendar"></i>',
			'submenu' => [
				'create' => [
					'role' => 'department_admin',
					'label' => 'navAddNew',
					'href' => '/capacity-demands/create',
					'icon' => '<i class="bi bi-calendar-plus"></i>',
				],
				'upload' => [
					'role' => 'department_admin',
					'label' => 'navUploadCapacityDemands',
					'href' => '/capacity-demands/upload',
					'icon' => '<i class="bi bi-upload"></i>'
				],
				'details' => [
					'role' => 'department_admin',
					'label' => 'navDetails',
					'href' => '/capacity-demands/details',
					'icon' => '<i class="bi bi-calendar-check"></i>',
				],
				'list' => [
					'role' => 'department_admin',
					'label' => 'navList',
					'href' => '/capacity-demands',
					'icon' => '<i class="bi bi-list"></i>',
				]
			]
		],
		'schedule' => [
			'role' => 'department_admin',
			'label' => 'navSchedule',
			'icon' => '<i class="bi bi-calendar-week"></i>',
			'href' => '/schedule'
		],
		'skills' => [
			'role' => 'user',
			'label' => 'navSkills',
			'icon' => '<i class="bi bi-gear-wide-connected"></i>',
			'href' => '/skills'
		],
		'my-restrictions' => [
			'role' => 'user',
			'label' => 'navMyRestrictions',
			'icon' => '<i class="bi bi-filter-square"></i>',
			'submenu' => [
				'current' => [
					'role' => 'user',
					'label' => 'navMyRestrictionsCurrent',
					'href' => '/my-restrictions',
					'icon' => '<i class="bi bi-filter-square"></i>',
				],
				'history' => [
					'role' => 'user',
					'label' => 'navMyRestrictionsHistory',
					'href' => '/my-restrictions/history',
					'icon' => '<i class="bi bi-list"></i>',
				],
			]
		],
		'profile' => [
			'label' => 'navProfile',
			'icon' => '<i class="bi bi-person"></i>',
			'href' => '/profile'
		],
		'user-schedule' => [
			'role' => 'user',
			'label' => 'navSchedule',
			'icon' => '<i class="bi bi-calendar-week"></i>',
			'href' => '/schedule/user'
		],
		'logout' => [
			'label' => 'navSignOut',
			'icon' => '<i class="bi bi-power"></i>',
			'href' => '/logout'
		]
	];
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'deleted_at' => [
				'type' => 'DATETIME',
				'null' => true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('departments');

		$this->forge->addField([
		'id' => [
			'type' => 'BIGINT',
			'unsigned' => true,
			'auto_increment' => true
		],
		'email' => [
			'type' => 'VARCHAR',
			'constraint' => 255,
			'unique' => true
		],
		'password' => [
			'type' => 'VARCHAR',
			'constraint' => 255
		],
		'firstname' => [
			'type' => 'VARCHAR',
			'constraint' => 100
		],
		'lastname' => [
			'type' => 'VARCHAR',
			'constraint' => 100
		],
		'bonus_point' => [
			'type' => 'INT',
			'default' => '0'
		],
		'role' => [
			'type' => 'VARCHAR',
			'constraint' => 50,
			'default' => 'user'
		],
		'department_id' => [
			'type' => 'INT',
			'unsigned' => true,
			'null' => true
		],
		'weekly_work_hours' => [
			'type' => 'DOUBLE',
			'constraint' => '10,2',
			'default' => '0'
		],
		'paid_annual_leave' => [
			'type' => 'DOUBLE',
			'constraint' => '10,2',
			'default' => '0'
		],
		'created_at' => [
			'type' => 'DATETIME',
			'null' => true
		],
		'updated_at' => [
			'type' => 'DATETIME',
			'null' => true
		],
		'deleted_at' => [
			'type' => 'DATETIME',
			'null' => true
		]
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('department_id','departments','id');
        $this->forge->createTable('users');

		$this->forge->addField([
		'id' => [
			'type' => 'INT',
			'unsigned' => true,
			'auto_increment' => true
		],
		'name' => [
			'type' => 'VARCHAR',
			'constraint' => 100
		],
		'created_at' => [
			'type' => 'DATETIME',
			'null' => true
		],
		'updated_at' => [
			'type' => 'DATETIME',
			'null' => true
		],
		'deleted_at' => [
			'type' => 'DATETIME',
			'null' => true
		]
		]);
		$this->forge->addKey('id', true);
        $this->forge->createTable('knowledge');

		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 50
			],
			'experience' => [
				'type' => 'INT',
				'comment' => 'Tapasztalati szint'
			],
			'display_order' => [
				'type' => 'INT',
				'default' => '0'
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'deleted_at' => [
				'type' => 'DATETIME',
				'null' => true
			]
		]);
		$this->forge->addKey('id', true);
        $this->forge->createTable('knowledge_levels');

		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'user_id' => [
				'type' => 'BIGINT',
				'unsigned' => true
			],
			'knowledge_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'knowledge_level_id' => [
				'type' => 'INT',
				'unsigned' => true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('user_id','users','id');
		$this->forge->addForeignKey('knowledge_id','knowledge','id');
		$this->forge->addForeignKey('knowledge_level_id','knowledge_levels','id');
        $this->forge->createTable('user_knowledge_levels');

		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			'start_time' => [
				'type' => 'TIME'
			],
			'end_time' => [
				'type' => 'TIME'
			],
			'display_order' => [
				'type' => 'INT',
				'default' => '0'
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'deleted_at' => [
				'type' => 'DATETIME',
				'null' => true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('shifts');

		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 100
			],
			'cost' => [
				'type' => 'INT',
				'default' => '0'
			],
			'active' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1'
			],
			'display_order' => [
				'type' => 'INT',
				'default' => '0'
			],
			'input_type' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => 'input'
			],
			'select_table' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'null' => true
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'deleted_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('restrictions');

		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'type' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => 'Korlátozás típusa'
			],
			'user_id' => [
				'type' => 'BIGINT',
				'unsigned' => true
			],
			'restriction_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'value' => [
				'type' => 'VARCHAR',
				'constraint' => 50
			],
			'bonus_point' => [
				'type' => 'INT',
				'default' => '0'
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('restriction_id','restrictions','id');
		$this->forge->addForeignKey('user_id','users','id');
		$this->forge->createTable('user_selected_restrictions');

		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'day' => [
				'type' => 'DATE'
			],
			'department_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'shift_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'knowledge_id' => [
				'type' => 'INT',
				'unsigned' => true,
				'comment' => 'Ha 0 akkor annyi ember kell, különben az adott tudásból kell annyi'
			],
			'knowledge_level_id' => [
				'type' => 'INT',
				'unsigned' => true,
				'comment' => '(Legalább) ilyen szint szükséges az adott tudásból, ha null, akkor mindegy a szint',
				'null' => true
			],
			'amount' => [
				'type' => 'INT',
				'default' => '0'
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('department_id','departments','id');
		$this->forge->addForeignKey('shift_id','shifts','id');
		$this->forge->addForeignKey('knowledge_id','knowledge','id');
		$this->forge->addForeignKey('knowledge_level_id','knowledge_levels','id');
		$this->forge->createTable('capacity_demand');

		$this->forge->addField([
			'id' => [
				'type' => 'BIGINT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'day' => [
				'type' => 'DATE'
			],
			'department_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'shift_id' => [
				'type' => 'INT',
				'unsigned' => true
			],
			'user_id' => [
				'type' => 'BIGINT',
				'unsigned' => true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('department_id','departments','id');
		$this->forge->addForeignKey('shift_id','shifts','id');
		$this->forge->addForeignKey('user_id','users','id');
		$this->forge->createTable('schedule');
	}

	public function down()
	{
		//
	}
}

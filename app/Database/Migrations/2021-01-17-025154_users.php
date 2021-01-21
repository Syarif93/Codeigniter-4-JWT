<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				'type'          => 'VARCHAR',
				'constraint'     => 100
			],
			"name" => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 50,
			],
			"email" => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 100,
				'unique'		=> true
			],
			"password" => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 255
			],
			"updated_at" => [
				'type'			=> 'DATETIME',
				'null' 			=> true
			],
			"created_at" => [
				'type'			=> 'DATETIME',
				'null'			=> true
			]
		]);

		$this->forge->addKey('id', true);

		$this->forge->createTable('users', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('users');
	}
}

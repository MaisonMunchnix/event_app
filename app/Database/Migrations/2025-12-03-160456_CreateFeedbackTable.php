<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'event_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'registration_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => 'Rating from 1 to 5'
            ],
            'comment' => [
                'type' => 'TEXT',
                'null' => true
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
        $this->forge->addKey('event_id');
        $this->forge->addKey('registration_id');
        $this->forge->createTable('feedback');
    }

    public function down()
    {
        $this->forge->dropTable('feedback');
    }
}

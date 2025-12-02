<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'totoy',
            'email' => 'totoy@example.com',
            'password' => password_hash('totoybiboy', PASSWORD_DEFAULT)
        ];
        

        $this->db->table('users')->insert($data);
    }
}

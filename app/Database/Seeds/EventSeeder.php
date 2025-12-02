<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $data = [
           [ 
            'title' => "Univesity Orientation 2025",
            'description' => "A university wide eveny introducing progress, campus guidelines, and student services.",
            'venue' => "Main Auditorium",
            'event_date' => "2025-02-15",
            'created_at' => date('Y-m-d H:i:s')
            ]
            ,
            [
            'title' => "Faculty Training on Digital Lietracy",
            'description' => "Training for teachers and staff to enchance digital skills and learning tools",
            'venue' => "Main Auditorium",
            'event_date' => "2025-03-10",
            'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => "Student Leadership Training",
            'description' => "Training for teachers and staff to enchance digital skills and learning tools",
            'venue' => "Main Auditorium",
            'event_date' => "2025-04-20",
            'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('events')->insertBatch($data);
    }
}

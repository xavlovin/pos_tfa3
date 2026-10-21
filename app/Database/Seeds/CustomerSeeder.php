<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $rows = [
            ['Maria Clara Santos', 'maria.santos@example.com', '0917 555 0101', 'Sampaloc, Manila'],
            ['Juan Miguel dela Cruz', 'juan.delacruz@example.com', '0918 555 0102', 'Quezon City'],
            ['Andrea Mae Villanueva', 'andrea.villanueva@example.com', '0927 555 0103', 'Pasig City'],
            ['Paolo Benedict Reyes', 'paolo.reyes@example.com', null, 'Makati City'],
            ['Katrina Joy Bautista', 'katrina.bautista@example.com', '0995 555 0105', null],
        ];

        $data = array_map(static fn ($r) => [
            'full_name'  => $r[0],
            'email'      => $r[1],
            'phone'      => $r[2],
            'address'    => $r[3],
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows);

        $this->db->table('customers')->insertBatch($data);
    }
}

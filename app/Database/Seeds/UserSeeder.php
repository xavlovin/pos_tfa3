<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now  = date('Y-m-d H:i:s');
        $hash = password_hash('password123', PASSWORD_DEFAULT);

        $rows = [
            ['admin', 'System Administrator', 'admin@pos.local', 'admin'],
            ['mgr.lopez', 'Carmela Lopez', 'carmela.lopez@pos.local', 'manager'],
            ['cashier01', 'Rafael Mendoza', 'rafael.mendoza@pos.local', 'cashier'],
            ['cashier02', 'Bea Francine Torres', null, 'cashier'],
        ];

        $data = array_map(static fn ($r) => [
            'username'      => $r[0],
            'full_name'     => $r[1],
            'email'         => $r[2],
            'role'          => $r[3],
            'avatar'        => null,
            'password_hash' => $hash,
            'created_at'    => $now,
            'updated_at'    => $now,
        ], $rows);

        $this->db->table('users')->insertBatch($data);
    }
}

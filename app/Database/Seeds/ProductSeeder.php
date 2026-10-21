<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $rows = [
            ['BEV-001', 'Iced Coffee 16oz', 'Beverages', 85.00, 120],
            ['BEV-002', 'Bottled Water 500ml', 'Beverages', 20.00, 300],
            ['SNK-001', 'Cheese Ensaymada', 'Bakery', 45.00, 40],
            ['SNK-002', 'Chicken Empanada', 'Bakery', 55.00, 8],
            ['MEA-001', 'Chicken Adobo Rice Bowl', 'Meals', 145.00, 25],
            ['MEA-002', 'Pork Sisig Rice Bowl', 'Meals', 160.00, 0],
        ];

        $data = array_map(static fn ($r) => [
            'sku'        => $r[0],
            'name'       => $r[1],
            'category'   => $r[2],
            'price'      => $r[3],
            'stock'      => $r[4],
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows);

        $this->db->table('products')->insertBatch($data);
    }
}

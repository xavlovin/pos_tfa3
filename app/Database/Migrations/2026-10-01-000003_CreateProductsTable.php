<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Products table (carried over from TFA2, read-only in this activity).
 */
class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sku'        => ['type' => 'VARCHAR', 'constraint' => 30],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 120],
            'category'   => ['type' => 'VARCHAR', 'constraint' => 60],
            'price'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => '0.00'],
            'stock'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('sku');
        $this->forge->createTable('products', true);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}

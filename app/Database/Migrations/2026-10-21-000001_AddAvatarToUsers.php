<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * TFA3: adds the avatar column to the users table.
 * Only the stored FILENAME is saved here — never the image data or a full path.
 */
class AddAvatarToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'role',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'avatar');
    }
}

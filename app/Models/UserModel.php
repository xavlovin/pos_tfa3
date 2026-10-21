<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'full_name', 'email', 'role', 'avatar', 'password_hash'];
    protected $useTimestamps = true;

    /** Roles a user account may hold. */
    public const ROLES = ['admin' => 'Administrator', 'manager' => 'Manager', 'cashier' => 'Cashier'];
}

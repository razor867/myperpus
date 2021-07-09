<?php

namespace App\Models;

use CodeIgniter\Model;

class M_authGroupsUsers extends Model
{
    protected $table      = 'auth_groups_users';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';

    protected $allowedFields = [
        'group_id',
        'user_id',
    ];
}

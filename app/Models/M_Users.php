<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Users extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'username',
        'email',
        'update_bio',
        // 'password',
        'firstname',
        'lastname',
        'nis',
        'jk',
        'tlp',
        'about',
    ];
}

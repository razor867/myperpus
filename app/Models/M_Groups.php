<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Groups extends Model
{
    protected $table      = 'auth_groups';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';

    protected $allowedFields = [
        'name',
        'description',
    ];
}

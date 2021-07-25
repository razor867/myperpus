<?php

namespace App\Models;

use CodeIgniter\Model;

class _Approval extends Model
{
    protected $table      = '_approval';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}

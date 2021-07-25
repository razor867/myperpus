<?php

namespace App\Models;

use CodeIgniter\Model;

class _Pengembalian extends Model
{
    protected $table      = '_pengembalian';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}

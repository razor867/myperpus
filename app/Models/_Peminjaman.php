<?php

namespace App\Models;

use CodeIgniter\Model;

class _Peminjaman extends Model
{
    protected $table      = '_peminjaman';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}

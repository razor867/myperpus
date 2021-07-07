<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Category extends Model
{
    protected $table      = 'category';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = ['nama', 'deskripsi', 'created_by', 'updated_by', 'deleted_by'];
}

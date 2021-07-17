<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Pengembalian extends Model
{
    protected $table      = 'pengembalian';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id_approval',
        'id_buku',
        'id_anggota',
        'tgl_dikembalikan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

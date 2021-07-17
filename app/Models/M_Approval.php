<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Approval extends Model
{
    protected $table      = 'approval';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id_buku',
        'id_anggota',
        'total_pinjam',
        'tgl_pinjam',
        'tgl_pengembalian',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

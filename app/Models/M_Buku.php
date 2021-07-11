<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Buku extends Model
{
    protected $table      = 'buku';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'judul',
        'penulis',
        'penerbit',
        'category_id',
        'jml_buku',
        'stok',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

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

    public function data_buku_convert()
    {
        $builder = $this->builder();
        $builder->select('buku.judul, buku.penulis, buku.penerbit, category.nama as kategori, buku.deskripsi, buku.jml_buku, buku.stok');
        $builder->join('category', 'category.id = buku.category_id', 'left');
        $builder->where(['buku.deleted_at' => NULL]);
        $builder->orderBy('buku.judul', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }
}

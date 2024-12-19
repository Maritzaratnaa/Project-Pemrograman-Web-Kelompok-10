<?php

namespace App\Models;

use CodeIgniter\Model;

class KatalogModel extends Model
{
    protected $table = 'katalog';
    protected $primaryKey = 'id_katalog';
    protected $allowedFields = ['nama_katalog'];
}

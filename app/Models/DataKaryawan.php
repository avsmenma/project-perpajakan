<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'data_karyawan';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'npwp';

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'npwp',
        'nama',
        'nik_sap',
        'nama_kebun',
        'bupot_a1',
        'link_pdf',
    ];
}

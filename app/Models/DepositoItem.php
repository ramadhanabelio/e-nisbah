<?php

namespace App\Models;

use App\Models\Surat;
use Illuminate\Database\Eloquent\Model;

class DepositoItem extends Model
{
    protected $fillable = [
        'surat_id',
        'nomor_rekening_deposito',
        'nominal',
        'jangka_waktu',
        'tanggal_penempatan_baru',
        'tanggal_perpanjangan',
        'tanggal_jatuh_tempo',
        'spesial_nisbah',
        'expected_return',
        'jenis_transaksi',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}

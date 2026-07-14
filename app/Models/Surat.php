<?php

namespace App\Models;

use App\Models\DepositoItem;
use App\Models\User;
use App\Models\WorkflowSurat;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'nomor_surat',
        'tanggal',
        'cabang',

        'nama_nasabah',
        'jenis_nasabah',

        'total_nominal',
        'total_relation_outstanding',
        'alasan',

        'created_by',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function depositoItems()
    {
        return $this->hasMany(DepositoItem::class);
    }

    public function workflow()
    {
        return $this->hasOne(WorkflowSurat::class);
    }
}

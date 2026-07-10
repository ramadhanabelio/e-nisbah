<?php

namespace App\Models;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ArsipSurat extends Model
{
    protected $table = 'arsip_surats';

    protected $fillable = [
        'surat_id',
        'admin_id',
        'arsip_at',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

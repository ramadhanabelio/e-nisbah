<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisiSurat extends Model
{
    protected $table = 'revisi_surats';

    protected $fillable = [
        'surat_id',
        'user_id',
        'catatan',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ApprovalSurat extends Model
{
    protected $fillable = [
        'surat_id',
        'user_id',
        'role',
        'status',
        'catatan',
        'approved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}

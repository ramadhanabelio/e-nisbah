<?php

namespace App\Models;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WorkflowSurat extends Model
{
    protected $fillable = [
        'surat_id',
        'current_role',
        'current_user_id',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function currentUser()
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }
}

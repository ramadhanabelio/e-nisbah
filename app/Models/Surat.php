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
        'lampiran',

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

    public static function terbilang($nilai)
    {
        $nilai = abs((float)$nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";

        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = self::terbilang($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = self::terbilang($nilai / 10) . " Puluh" . self::terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . self::terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::terbilang($nilai / 100) . " Ratus" . self::terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . self::terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::terbilang($nilai / 1000) . " Ribu" . self::terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::terbilang($nilai / 1000000) . " Juta" . self::terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::terbilang($nilai / 1000000000) . " Milyar" . self::terbilang(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::terbilang($nilai / 1000000000000) . " Trilyun" . self::terbilang(fmod($nilai, 1000000000000));
        }

        return trim($temp);
    }

    public function getTotalRelationOutstandingTerbilangAttribute()
    {
        if (empty($this->total_relation_outstanding)) {
            return 'Nol Rupiah';
        }
        return self::terbilang($this->total_relation_outstanding) . ' Rupiah';
    }
}

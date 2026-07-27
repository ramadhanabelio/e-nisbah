<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposito_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_rekening_deposito');
            $table->decimal('nominal', 20, 2);
            $table->string('jangka_waktu');
            $table->date('tanggal_penempatan_baru')->nullable();
            $table->date('tanggal_perpanjangan')->nullable();
            $table->date('tanggal_jatuh_tempo');
            $table->string('spesial_nisbah');
            $table->string('expected_return');
            $table->string('jenis_transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposito_items');
    }
};

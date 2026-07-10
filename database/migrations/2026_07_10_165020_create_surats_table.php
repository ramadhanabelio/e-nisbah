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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal');
            $table->string('perihal');
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 20, 2);
            $table->string('file_surat');
            $table->foreignId('created_by')
                ->constrained('users');
            $table->enum('status', [
                'draft',
                'proses',
                'revisi',
                'selesai'
            ])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};

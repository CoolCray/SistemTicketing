<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Makanan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->foreignId('makanan_id')->constrained('makanan')->cascadeOnDelete();
            $table->foreignId('minuman_id')->constrained('minuman')->cascadeOnDelete();
            $table->foreignId('additional_id')->constrained('additional')->cascadeOnDelete();
            $table->integer('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};

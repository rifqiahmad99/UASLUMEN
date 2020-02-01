<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul_buku', 50);
            $table->string('penerbit', 50);
            $table->string('tahun_terbit', 5);
            $table->string('penulis',50);
            $table->text('deskripsi');
            $table->integer('harga', 10);
            $table->string('foto', 100)->nullable();
            $table->integer('id_distributor')->index('id_distributor_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}

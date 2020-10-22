<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBerita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_berita', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 50);
            $table->string('singkat', 200);
            $table->text('content');
            $table->date('tanggal')->default("2000-01-01");
            $table->date('mulai')->default("2000-01-01");
            $table->date('selesai')->default("2000-01-01");
            $table->string('updated', 50)->nullable();
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
        Schema::dropIfExists('m_berita');
    }
}

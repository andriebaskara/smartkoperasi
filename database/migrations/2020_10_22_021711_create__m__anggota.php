<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMAnggota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_anggota', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nama');
            $table->string('no_anggota', 20)->unique()->nullable();
            $table->string('email', 100)->unique();
            $table->string('password', 200);
            $table->string('telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->integer('lokasi_id');
            $table->integer('status_id')->nullable();
            $table->integer('is_anggota')->nullable()->comment('1 anggota 0 non anggota');
            $table->string('token', 70)->nullable()->unique();
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
        Schema::dropIfExists('m_anggota');
    }
}

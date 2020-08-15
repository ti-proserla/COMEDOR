<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',30)->nullable();
            $table->string('apellido',50)->nullable();
            $table->string('usuario',10);
            $table->string('password',20);
            $table->string('api_token',22);
            $table->enum('estado',["0","1","2"]);//0:activo , 1:inactivo, 2:Cuenta Principal
            $table->string('rol',20); //ADMINISTRADOR tiene que cambiar empresa
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuenta');
    }
}

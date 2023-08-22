<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            /**
             * Para criar a FK é preciso que o campo de origem, neste
             * o created_by, seja criado igualmente à sua referência
             * na tabela primária, neste caso users;
             * 
             * O id da tabela users é bigint e unsigned.
             */
            $table->bigInteger('created_by')->unsigned()->nullable();
            /**
             * Aqui se realiza a criação da FK entre a tabela grupos
             * e a tabela users.
             */
            $table
                ->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
        Schema::dropIfExists('grupos');
    }
}

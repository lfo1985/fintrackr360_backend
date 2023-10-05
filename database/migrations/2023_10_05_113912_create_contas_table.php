<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_grupo')->unsigned()->nullable();
            $table->string('titulo');
            $table->enum('natureza', ['C', 'D']);
            $table->text('descricao');
            $table->decimal('valor', 10, 2);
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();
            $table
                ->foreign('id_grupo')
                ->references('id')
                ->on('grupos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreign('created_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas');
    }
}

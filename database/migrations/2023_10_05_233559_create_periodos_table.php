<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_conta')->unsigned()->nullable();
            $table->integer('numero');
            $table->integer('total');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->enum('status', ['PAGO', 'PENDENTE'])->default('PENDENTE');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->timestamps();

            $table
                ->foreign('id_conta')
                ->references('id')
                ->on('contas')
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
        Schema::dropIfExists('periodos');
    }
}

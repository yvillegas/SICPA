<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaNotaCredito extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('t_notacredito', function(Blueprint $table)
		{
			$table->increments('ncred_id');
			$table->decimal('ncred_tot',12,2);
			$table->decimal('ncred_saldo',12,2);
			$table->integer('comp_id')->unsigned();
			$table->foreign('comp_id')->references('comp_id')->on('t_comprobante');
			$table->string('ncred_num');
			$table->string('ncred_obs');
			$table->string('ncred_tipo');
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
		Schema::drop('t_notacredito');
	}

}

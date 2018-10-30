<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{

	public function up()
	{
		Schema::create($this->getTable(), function (Blueprint $table) {
			$table->engine = "innoDB";
			$table->increments('id');
			$table->string('portname');
			$table->decimal('price', 15, 2);
			$table->string('refrenseid', 100)->nullable();
			$table->string('tracknumber', 50)->nullable();
			$table->string('cardnumber', 50)->nullable();
			$table->string('ip', 20)->nullable();
			$table->timestamp('paymentdate')->nullable();
			$table->nullableTimestamps();
			$table->int('status');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions);
	}
}

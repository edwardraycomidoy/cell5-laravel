<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collections', function(Blueprint $table) {
			$table->id();
			$table->foreignId('member_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('beneficiary_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
			$table->date('due_on');
			$table->date('released_on')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('collections');
	}
}

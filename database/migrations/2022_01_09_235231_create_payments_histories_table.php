<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_histories', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->string('currency');
            $table->string('externalId');
            $table->string('partyIdType');
            $table->string('partyId');
            $table->string('payerMessage');
            $table->string('payeeMessage');
            $table->rememberToken();
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
        Schema::dropIfExists('payments_histories');
    }
}

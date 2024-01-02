<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->string('code', 12)->primary();
            $table->string('status', 12)->default('PENDING');
            $table->bigInteger('user_requested');
            $table->bigInteger('requested_region_id');
            $table->bigInteger('requested_division_id');
            $table->text('description')->nullable();
            $table->string('document', 32)->nullable();
            $table->bigInteger('user_created');
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
        Schema::dropIfExists('payment_requests');
    }
}

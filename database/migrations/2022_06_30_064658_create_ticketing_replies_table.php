<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketingRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketing_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // user yg membalas tiket
            $table->unsignedBigInteger('ticketing_id');
            $table->text('remarks');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ticketing_id')->references('id')->on('ticketings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketing_replies');
    }
}

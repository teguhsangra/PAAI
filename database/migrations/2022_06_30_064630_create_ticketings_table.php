<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); // user yg membuat tiket
            $table->string('code')->unique();
            $table->enum('is_closed', ['N', 'Y']);
            $table->string('subject')->nullable(); // diisi jika ticket_subject id tidak diisi
            $table->text('remarks');
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('closed_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketings');
    }
}

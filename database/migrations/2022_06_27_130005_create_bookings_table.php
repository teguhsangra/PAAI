<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('member_id');
            $table->string('code')->unique();
            $table->unsignedBigInteger('product_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('total', 20, 2)->default(0);
            $table->integer('term_notice_period')->default(0);
            $table->string('attachment')->nullable();
            $table->enum('is_renewal', ['N', 'Y']);
            $table->enum('renewal_status', ['on renewal', 'ready to renew','ready to terminate'])->default('on renewal');
            $table->enum('payment_status', ['not paid', 'paid','waiting verified'])->default('waiting verified');
            $table->text('remarks')->nullable();
            $table->string('discard_or_cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('draft_by')->nullable();
            $table->string('posting_by')->nullable();
            $table->string('discard_by')->nullable();
            $table->string('complete_by')->nullable();
            $table->string('cancel_by')->nullable();
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}

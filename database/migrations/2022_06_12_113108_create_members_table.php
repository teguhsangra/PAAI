<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->string('code')->unique();
            $table->string('aaji');
            $table->string('name');
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('picture')->nullable();
            $table->string('url_facebook')->nullable();
            $table->string('url_instagram')->nullable();
            $table->string('url_twitter')->nullable();
            $table->string('url_youtube')->nullable();
            $table->enum('is_verified', ['N', 'Y'])->default('N');
            $table->string('referral')->nullable();
            $table->date('expired_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('members');
    }
}

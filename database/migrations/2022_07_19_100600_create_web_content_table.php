<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_content', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['text', 'logo','image','map']);
            $table->string('picture_1')->nullable();
            $table->string('picture_2')->nullable();
            $table->text('title');
            $table->text('desc');
            $table->text('link_location');
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
        Schema::dropIfExists('web_content');
    }
}

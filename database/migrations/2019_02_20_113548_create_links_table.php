<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table Links with information about crawl links
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('checked_url');
            $table->string('presence_url');
            $table->string('checked_url_status_code')->nullable();
            $table->string('presence_url_status_code')->nullable();
            $table->dateTime('presence_url_added')->nullable();
            $table->dateTime('presence_url_removed')->nullable();
            $table->dateTime('last_check')->nullable();
            $table->string('email')->nullable();
            $table->text('details')->nullable();
            $table->boolean('active')->default(true);
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
        // Remove table Links if exists
        Schema::dropIfExists('links');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The table Roles for the separation of user rights
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique(); // Name of the Role
            $table->timestamps();
        });
        // The table User-Roles for connecting users with roles
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->boolean('active')->default(true); // Is the current role active for the user
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
        // Delete table Roles if exists
        Schema::dropIfExists('roles');
        // Delete table User-Roles if exists
        Schema::dropIfExists('user_roles');
    }
}

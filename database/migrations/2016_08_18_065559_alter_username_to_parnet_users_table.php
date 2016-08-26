<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsernameToParnetUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_users', function (Blueprint $table) {
            $table->dropUnique('partner_users_username_unique');
            $table->string('username')->change();
        });
    }
}

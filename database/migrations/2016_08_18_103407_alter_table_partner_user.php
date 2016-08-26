<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePartnerUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_users', function (Blueprint $table) {
            $table->tinyInteger('activated')
                  ->unsigned()
                  ->default(0)
                  ->after('password')
                  ->comment('0 --> Not Activated Yet; 1 --> Activated; 2 --> Deactivated');
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
            $table->dropColumn('activated');
        });
    }
}

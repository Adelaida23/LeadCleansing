<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLastChechedToLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // DB::raw('CURRENT_TIMESTAMP') probar
            $table->dateTime('last_checked')->nullable()->default(DB::raw('created_at'))->after('subid3');
            //  $table->dateTime('last_checked')->nullable()->default('created_at')->after('subid3');
            //quitar nullable
            // $table->dateTime('last_checked')->nullable()->default('2020-01-01 00:00:00')->after('subid3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('last_checked');
        });
    }
}

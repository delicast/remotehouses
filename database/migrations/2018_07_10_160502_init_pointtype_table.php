<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitPointtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pointtype')->insert([array(
            'id' => 0,
            'name' => 'Household'

        ),array(
            'id' => 1,
            'name' => 'Waterhole'

        )]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pointtype', function (Blueprint $table) {
            //
        });
    }
}

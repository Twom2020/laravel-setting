<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TwomSettingable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("twom_settingable", function (Blueprint $table) {
            $table->increments("id");
            $table->string("key");
            $table->text("value")->nullable();
            $table->integer("settingable_id");
            $table->string("settingable_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twom_settingable');
    }
}

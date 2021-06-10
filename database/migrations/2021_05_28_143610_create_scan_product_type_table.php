<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScanProductTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scanProductType', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedTinyInteger('active')->default(1);
            $table->string('code', 50);
            $table->unsignedInteger('box')->default(1);
            $table->unsignedInteger('group')->default(1);
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('token',50)->default('');
            $table->tinyInteger('is_confirmed')->default(1);
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
        Schema::dropIfExists('scanProductType');
    }
}

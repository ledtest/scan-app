<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSamsungOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samsungOuts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('product_type_id')->default(0);
            $table->unsignedInteger('scan_order')->default(0)->comment('扫描序号');
            $table->string('pn', 50)->default('')->comment('类型');
            $table->string('code', 50)->default('');
            $table->unsignedTinyInteger('box')->default(1);
            $table->unsignedTinyInteger('group')->default(1);
            $table->string('lot', 50)->default('');
            $table->string('qty', 10)->default('');
            $table->string('dc', 50)->default('');
            $table->string('origin_place',20)->default('');
            $table->string('po',50)->default('');
            $table->unsignedTinyInteger('printed_nums')->default(0);
            $table->unsignedInteger('reprinted_nums')->default(0);
            $table->tinyInteger('is_confirmed')->default(0);
            $table->string('token',50)->default('');
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
        Schema::dropIfExists('samsungOuts');
    }
}

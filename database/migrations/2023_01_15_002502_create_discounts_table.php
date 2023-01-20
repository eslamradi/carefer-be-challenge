<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->unique();
            $table->integer('seats_count');
            $table->float('percentage', 8, 2)->default('0');
            $table->float('amount', 8, 2)->default('0');
            $table->float('max_amount', 8, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('status')->default(true);
            $table->boolean('trash')->default(false);
            $table->timestamps();
        });

        Schema::create('metables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('meta_id')->unsigned();
            $table->bigInteger('metable_id')->unsigned();
            $table->string('metable_type');
            $table->timestamps();

            $table->unique(['meta_id', 'metable_id', 'metable_type']);
            $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
        Schema::dropIfExists('metables');
    }
}

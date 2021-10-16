<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('line_id');
            $table->string('line_name');
            $table->integer('q10')->nullable();
            $table->integer('q11')->nullable();
            $table->integer('q12')->nullable();
            $table->integer('q13')->nullable();
            $table->integer('q14')->nullable();
            $table->integer('q15')->nullable();
            $table->integer('q16')->nullable();
            $table->integer('q17')->nullable();
            $table->integer('q18')->nullable();
            $table->integer('q19')->nullable();
            $table->integer('q20')->nullable();
            $table->integer('q21')->nullable();
            $table->integer('q22')->nullable();
            $table->integer('q23')->nullable();
            $table->integer('q24')->nullable();
            $table->integer('q25')->nullable();
            $table->integer('q26')->nullable();
            $table->integer('q27')->nullable();
            $table->integer('q28')->nullable();
            $table->integer('q29')->nullable();
            $table->integer('q30')->nullable();
            $table->integer('q31')->nullable();
            $table->integer('q32')->nullable();
            $table->integer('q33')->nullable();
            $table->integer('q34')->nullable();
            $table->integer('q35')->nullable();
            $table->integer('q36')->nullable();
            $table->integer('q37')->nullable();
            $table->integer('q38')->nullable();
            $table->integer('q39')->nullable();
            $table->integer('q40')->nullable();
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
        Schema::dropIfExists('tests');
    }
}

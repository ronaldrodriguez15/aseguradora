<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsurersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('no_poliza');
            $table->string('document_path')->nullable();
            $table->string('val_incapacidad')->nullable();
            $table->string('val_vida')->nullable();
            $table->string('val_previexequial')->nullable();
            $table->string('val_banco')->nullable();
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('insurers');
    }
}

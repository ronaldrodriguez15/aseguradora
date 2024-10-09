<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsSignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('documents_signed', function (Blueprint $table) {
        $table->id();
        $table->string('file_name');
        $table->string('signed_id');
        $table->string('document_path');
        $table->string('inability_id');
        $table->timestamp('expires');
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
        Schema::dropIfExists('documents_signed');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simplecms_storage_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('entidad_id');
            $table->text('atributos');
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
        Schema::dropIfExists('simplecms_storage_items');
    }
}

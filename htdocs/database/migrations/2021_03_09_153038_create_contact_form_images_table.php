<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactFormImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_form_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_form_id');
            $table->string('file_name', 100);
            $table->timestamps();

            // 外部キー制約
            $table->foreign('contact_form_id')->references('id')->on('contact_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_form_images');
    }
}

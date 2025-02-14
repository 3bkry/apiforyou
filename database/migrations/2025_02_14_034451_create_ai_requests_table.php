<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();  // Primary Key
            $table->string('content_id')->unique();  // Unique identifier for the request
            $table->string('content');  // Content description
            $table->string('status')->default('pending');  // Status of the request (pending, completed, failed)
            $table->string('image_url')->nullable();  // The image URL, can be nullable for unfinished requests
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_requests');
    }
}

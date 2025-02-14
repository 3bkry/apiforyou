<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContentColumnInAiRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ai_requests', function (Blueprint $table) {
            // Change 'content' column type to TEXT
            $table->text('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ai_requests', function (Blueprint $table) {
            // Revert 'content' column to VARCHAR(255) in case of rollback
            $table->string('content', 255)->change();
        });
    }
}

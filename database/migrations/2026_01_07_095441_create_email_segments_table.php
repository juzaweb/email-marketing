<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_segments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->datetimes();
        });

        Schema::create('email_segment_subscriber', function (Blueprint $table) {
            $table->foreignUuid('segment_id')->constrained('email_segments')->onDelete('cascade');
            $table->foreignUuid('subscriber_id')->constrained('email_subscribers')->onDelete('cascade');
            $table->primary(['segment_id', 'subscriber_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_segment_subscriber');
        Schema::dropIfExists('email_segments');
    }
};

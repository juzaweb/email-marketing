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
        if (Schema::connection($this->connection)->hasTable('email_segments')) {
            return;
        }

        Schema::connection($this->connection)->create('email_segments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->websiteId();
            $table->datetimes();
        });

        Schema::connection($this->connection)->create('email_segment_subscriber', function (Blueprint $table) {
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
        Schema::connection($this->connection)->dropIfExists('email_segment_subscriber');
        Schema::connection($this->connection)->dropIfExists('email_segments');
    }
};

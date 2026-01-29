<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_automation_events', function (Blueprint $table) {
            $table->id();
            $table->string('trigger_code');
            $table->uuidMorphs('object_id');
            $table->dateTime('occurred_at');
            $table->json('payload')->nullable();
            $table->websiteId();
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_automation_events');
    }
};

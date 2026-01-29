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
        if (Schema::connection($this->connection)->hasTable('email_automation_rules')) {
            return;
        }

        Schema::connection($this->connection)->create('email_automation_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('trigger_type'); // Dynamic trigger type from registry
            $table->boolean('active')->default(true);
            $table->json('conditions')->nullable(); // Additional conditions/filters
            $table->integer('delay_hours')->default(0); // Delay before sending (in hours)
            $table->websiteId();
            $table->datetimes();

            $table->index(['trigger_type', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists('email_automation_rules');
    }
};

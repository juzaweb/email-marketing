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
        if (Schema::connection($this->connection)->hasTable('email_subscribers')) {
            return;
        }

        Schema::connection($this->connection)->create('email_subscribers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 190)->index();
            $table->string('name')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('status', ['subscribed', 'unsubscribed', 'bounced'])->default('subscribed')->index();
            $table->websiteId();
            $table->datetimes();

            $table->unique(['email', 'website_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists('email_subscribers');
    }
};

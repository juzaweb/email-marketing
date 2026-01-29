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
        if (Schema::connection($this->connection)->hasTable('email_marketing_templates')) {
            return;
        }

        Schema::connection($this->connection)->create('email_marketing_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('content'); // HTML content
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
        Schema::connection($this->connection)->dropIfExists('email_marketing_templates');
    }
};

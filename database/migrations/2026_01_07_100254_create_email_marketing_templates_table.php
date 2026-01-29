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
        if (Schema::hasTable('email_marketing_templates')) {
            return;
        }

        Schema::create('email_marketing_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('content'); // HTML content
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
        Schema::dropIfExists('email_marketing_templates');
    }
};

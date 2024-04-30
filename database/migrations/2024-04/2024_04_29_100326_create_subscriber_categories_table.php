<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'email_marketing_subscriber_categories',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->timestamps();
            }
        );

        Schema::create(
            'email_marketing_subscriber_category',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('subscriber_id')
                    ->constrained('email_marketing_subscribers')
                    ->onDelete('cascade');
                $table->foreignId('subscriber_category_id')
                    ->constrained('email_marketing_subscriber_categories')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('email_marketing_subscriber_categories');
    }
};

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
            'email_marketing_email_lists',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            }
        );

        Schema::create(
            'email_marketing_email_list_subscriber',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('email_list_id')
                    ->constrained('email_marketing_email_lists');
                $table->foreignId('subscriber_id')
                    ->constrained('email_marketing_subscribers');
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
        Schema::dropIfExists('email_marketing_email_list_subscriber');
        Schema::dropIfExists('email_marketing_email_lists');
    }
};

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
        if (Schema::hasTable('email_automation_logs')) {
            return;
        }

        Schema::create('email_automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('automation_rule_id')->constrained('email_automation_rules')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // User or Member ID
            $table->string('user_type'); // 'user' or 'member'
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->dateTime('scheduled_at')->nullable(); // When to send
            $table->dateTime('sent_at')->nullable(); // When actually sent
            $table->datetimes();

            $table->index(['automation_rule_id', 'user_id', 'user_type']);
            $table->index(['status', 'scheduled_at']);
            $table->index('website_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_automation_logs');
    }
};

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
        Schema::create('email_campaign_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('campaign_id')->constrained('email_campaigns')->onDelete('cascade');
            $table->foreignUuid('subscriber_id')->constrained('email_subscribers')->onDelete('cascade');
            $table->enum('type', ['sent', 'opened', 'clicked', 'bounced', 'complained'])->index();
            $table->string('link_url')->nullable(); // Link nào được click?
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('created_at');

            $table->index(['campaign_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_campaign_trackings');
    }
};

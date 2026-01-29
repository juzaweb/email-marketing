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
        if (Schema::connection($this->connection)->hasTable('email_campaigns')) {
            return;
        }

        Schema::connection($this->connection)->create('email_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('template_id')->nullable()->constrained('email_marketing_templates')->onDelete('set null');
            $table->string('name');
            $table->string('subject');
            $table->longText('content');
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'paused'])->default('draft');
            $table->bigInteger('views')->default(0);
            $table->bigInteger('clicks')->default(0);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->datetimes();
        });

        Schema::connection($this->connection)->create('email_segment_campaign', function (Blueprint $table) {
            $table->foreignUuid('segment_id')->constrained('email_segments')->onDelete('cascade');
            $table->foreignUuid('campaign_id')->constrained('email_campaigns')->onDelete('cascade');
            $table->primary(['segment_id', 'campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists('email_campaigns');
    }
};

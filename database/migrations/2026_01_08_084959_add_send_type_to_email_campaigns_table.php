<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->enum('send_type', ['manual', 'auto'])->default('manual')->after('template_id');

            // Automation fields (only used when send_type = 'auto')
            $table->string('automation_trigger_type')->nullable()->after('send_type');
            $table->json('automation_conditions')->nullable()->after('automation_trigger_type');
            $table->integer('automation_delay_hours')->default(0)->after('automation_conditions');

            $table->index(['send_type', 'automation_trigger_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropIndex(['send_type', 'automation_trigger_type']);
            $table->dropColumn([
                'send_type',
                'automation_trigger_type',
                'automation_conditions',
                'automation_delay_hours',
            ]);
        });
    }
};

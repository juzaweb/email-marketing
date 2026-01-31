<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->foreignUuid('website_id')->nullable()->constrained('websites')->onDelete('cascade');
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
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropForeign(['website_id']);
            $table->dropIndex(['website_id']);
            $table->dropColumn('website_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('reminders', function (Blueprint $table) {
            $table->boolean('email_sent')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('reminders', function (Blueprint $table) {
            // in case of rolling back was needed !!
            $table->dropColumn('email_sent');
        });
    }
};

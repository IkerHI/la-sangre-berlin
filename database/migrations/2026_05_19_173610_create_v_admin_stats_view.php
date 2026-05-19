<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return; // VIEW uses MySQL syntax; skip in SQLite test environment
        }

        // This VIEW is for direct DB inspection (phpMyAdmin) only.
        // The StatsController reads data independently via Eloquent.
        DB::statement("
            CREATE OR REPLACE VIEW v_admin_stats AS
            SELECT
                COALESCE((SELECT value FROM site_stats WHERE metric = 'total_visits'), 0) AS total_visits,
                (SELECT COUNT(*) FROM contact_messages WHERE \`read\` = 0)               AS unread_messages,
                (SELECT COUNT(*) FROM contact_messages)                                   AS total_messages,
                (SELECT COUNT(*) FROM concerts)                                           AS total_concerts,
                (SELECT COUNT(*) FROM concerts WHERE date >= NOW())                       AS upcoming_concerts,
                COALESCE((SELECT SUM(views) FROM concerts), 0)                           AS total_concert_views
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('DROP VIEW IF EXISTS v_admin_stats');
    }
};

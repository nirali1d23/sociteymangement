<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateSocietyData extends Command
{
    protected $signature = 'society:truncate-data';
    protected $description = 'Truncate society management system data every 24 hours';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 1️⃣ Delete users except user_type = 1
        DB::table('users')->where('user_type', '!=', 0)->delete();

        // 2️⃣ Tables to truncate
        $tables = [
            'allotments',
            'amenities',
            'bookamenities',
            'events',
            'event_feedback',
            'flats',
            'houses',
            'maintancebilllists',
            'maintancebills',
            'maintances',
            'maintance_processes',
            'notices',
            'notice_comments',
            'polloptions',
            'pollquestions',
            'pollsurveys',
            'preapprovals',
            'visitors',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Society data truncated successfully.');
    }
}

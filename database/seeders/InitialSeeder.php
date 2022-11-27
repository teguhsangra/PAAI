<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DELETE DATA TO STATUSES TABLE */
        DB::table('statuses')->delete();
        /* INSERT DATA TO STATUSES TABLE */
        DB::table('statuses')->insert([ // To Access This status need read, create,
            'name' => 'open',
            'action' => 'draft'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, update
            'name' => 'posted',
            'action' => 'posting'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, delete
            'name' => 'discard',
            'action' => 'discard'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, update, isExec
            'name' => 'complete',
            'action' => 'complete'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, de;ete, isExec
            'name' => 'void',
            'action' => 'cancel'
        ]);
    }
}

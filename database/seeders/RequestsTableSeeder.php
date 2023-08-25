<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('rzd_requests'->insert([
            'id'=>'1',
            'request_data' =>'test_request_data'
            
        ]);
        
    }
}

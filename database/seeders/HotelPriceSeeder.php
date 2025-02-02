<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HotelPriceSeeder extends Seeder
{
public function run()
    {
        DB::table('hotel_prices')->insert(
            [
                [
                    'hotels_id'=>1,
                    'rooms_id'=>1,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>1,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>1,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>2,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 5500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>2,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 6000000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>2,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 6500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>3,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 7000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>3,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 7500000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>1,
                    'rooms_id'=>3,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 8000000,
                    'author' => 1,
                ],


                [
                    'hotels_id'=>2,
                    'rooms_id'=>4,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 8500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>4,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 9000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>4,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 9500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>5,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 10000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>5,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 15000000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>5,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 20000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>6,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 25000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>6,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 30000000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>2,
                    'rooms_id'=>6,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 35000000,
                    'author' => 1,
                ],



                [
                    'hotels_id'=>3,
                    'rooms_id'=>7,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 40000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>7,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 45000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>7,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 50000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>8,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 55000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>8,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 60000000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>8,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 65000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>9,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 70000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>9,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 75000000,
                    'author' => 1,
                    
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>9,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>10,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>10,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>3,
                    'rooms_id'=>10,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],



                [
                    'hotels_id'=>4,
                    'rooms_id'=>11,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>11,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>11,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>12,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>12,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>12,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>13,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>13,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>13,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>14,
                    'start_date'=>'2023-01-01',
                    'end_date'=>'2023-04-30',
                    'markup'=>'33',
                    'contract_rate' => 4000000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>14,
                    'start_date'=>'2023-05-01',
                    'end_date'=>'2023-09-30',
                    'markup'=>'33',
                    'contract_rate' => 4500000,
                    'author' => 1,
                ],
                [
                    'hotels_id'=>4,
                    'rooms_id'=>14,
                    'start_date'=>'2023-10-01',
                    'end_date'=>'2023-12-31',
                    'markup'=>'33',
                    'contract_rate' => 5000000,
                    'author' => 1,
                ],
                
                
            ]);
        }
}

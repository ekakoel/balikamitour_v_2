<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdersSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('orders')->insert(
            [
                [
                    'orderno' => 'ORD220928-29HP',
                    'rsv_id'=> 1,
                    'user_id' => 1,
                    'name' => 'Admin',
                    'servicename'=>'Amarterra Villas Bali',
                    'email'=>"admin@admin.com",
                    'number_of_guests'=>2,
                    'subservice'=>"Three Bedroom Villa",
                    'checkin' =>'2022-12-01',
                    'checkout' => '2022-12-03',
                    'include'=> 'Breakfast, SPA (2 Hours), Coffee Time (18.00)',
                    'additional_info'=>'-',
                    'duration' => '2',
                    'capacity'=> '2',
                    'price_pax'=> "100",
                    'price_total'=>"200",
                    'service' => 'Hotel',
                    'note' => 'Tambah Bed',
                    'number_of_guests' => 2,
                    'guest_detail'=> "1. Andy<br>2. Any",
                    'arrival_flight'=>"QZ 557",
                    'arrival_time' => "3:28 pm",
                    'departure_flight'=>"QZ 557",
                    'departure_time' => "3:28 pm",
                    'status' => "Waiting",
                ],
                [
                    'orderno' => 'ORD220928-30HP',
                    'rsv_id'=> 1,
                    'user_id' => 1,
                    'name' => 'Admin',
                    'servicename'=>'Alila Seminyak',
                    'email'=>"admin@admin.com",
                    'number_of_guests'=>2,
                    'subservice'=>"Ocean View Suite",
                    'checkin' =>'2022-12-03',
                    'checkout' => '2022-12-05',
                    'include'=> 'Breakfast, SPA (2 Hours), Coffee Time (18.00)',
                    'additional_info'=>'-',
                    'duration' => '2',
                    'capacity'=> '2',
                    'price_pax'=> "100",
                    'price_total'=>"200",
                    'service' => 'Hotel',
                    'note' => 'Tambah Bed',
                    'number_of_guests' => 2,
                    'guest_detail'=> "1. Andy<br>2. Any",
                    'arrival_flight'=>"QZ 557",
                    'arrival_time' => "3:28 pm",
                    'departure_flight'=>"QZ 557",
                    'departure_time' => "3:28 pm",
                    'status' => "Waiting",
                ],
                [
                    'orderno' => 'ORD220928-31HP',
                    'rsv_id'=> 1,
                    'user_id' => 1,
                    'name' => 'Admin',
                    'servicename'=>'Amankila',
                    'email'=>"admin@admin.com",
                    'number_of_guests'=>2,
                    'subservice'=>"Ocean View",
                    'checkin' =>'2022-12-05',
                    'checkout' => '2022-12-07',
                    'include'=> 'Breakfast, SPA (2 Hours), Coffee Time (18.00)',
                    'additional_info'=>'-',
                    'duration' => '2',
                    'capacity'=> '2',
                    'price_pax'=> "100",
                    'price_total'=>"200",
                    'service' => 'Hotel',
                    'note' => 'Tambah Bed',
                    'number_of_guests' => 2,
                    'guest_detail'=> "1. Andy<br>2. Any",
                    'arrival_flight'=>"QZ 557",
                    'arrival_time' => "3:28 pm",
                    'departure_flight'=>"QZ 557",
                    'departure_time' => "3:28 pm",
                    'status' => "Waiting",
                ],
                [
                    'orderno' => 'ORD220928-32HP',
                    'rsv_id'=> 1,
                    'user_id' => 1,
                    'name' => 'Admin',
                    'servicename'=>'North Bali 2D/1N',
                    'email'=>"admin@admin.com",
                    'number_of_guests'=>2,
                    'subservice'=>"",
                    'checkin' =>'2022-12-02',
                    'checkout' => '2022-12-02',
                    'include'=> '-',
                    'additional_info'=>'-',
                    'duration' => '2',
                    'capacity'=> '2',
                    'price_pax'=> "100",
                    'price_total'=>"200",
                    'service' => 'Hotel',
                    'note' => 'Tambah Bed',
                    'number_of_guests' => 2,
                    'guest_detail'=> "1. Andy<br>2. Any",
                    'arrival_flight'=>"QZ 557",
                    'arrival_time' => "3:28 pm",
                    'departure_flight'=>"QZ 557",
                    'departure_time' => "3:28 pm",
                    'status' => "Waiting",
                ],
            ]);
    }
}

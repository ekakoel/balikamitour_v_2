<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToursSeeder extends Seeder
{
    public function run()
    {
        DB::table('tours')->insert(
            [
                [
                    'name' => 'North Bali 2D/1N',
                    'partners_id' => 1,
                    'destinations' => 'Bedugul, Lovina, Labuan Lalang',
                    'location' => 'Bali',
                    'type'=>'Group',
                    'duration' =>'2D/1N',
                    'code'=> Str::random(26),
                    'description' => 'The charm of this car. Choose CIMA. It is the pride and pleasure of choosing one vertex.',
                    'itinerary'=> 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'include'=> '1.Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>2. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'additional_info' => '* Minimum 2 orang <br> * valid for domestic guest only',
                    'contract_rate' => rand(1000000, 9000000),
                    'markup' => 5,
                    'qty' => '5',
                    'status' => 'Active',
                    'author_id' => 1,
                    'cover'=> '1.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Nusa Penida 2D/1N',
                    'partners_id' => 1,
                    'destinations' => 'Kelingking Beach, Diamond Beach, Cristal Bay',
                    'location' => 'Bali',
                    'type'=>'Couple',
                    'duration' =>'2D/1N',
                    'code'=> Str::random(26),
                    'description' => 'The charm of this car. Choose CIMA. It is the pride and pleasure of choosing one vertex.',
                    'itinerary'=> 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'include'=> '1.Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>2. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'additional_info' => '* Minimum 2 orang <br> * valid for domestic guest only',
                    'contract_rate' => rand(1000000, 9000000),
                    'markup' => 5,
                    'qty' => '2',
                    'status' => 'Active',
                    'author_id' => 1,
                    'cover'=> '2.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'West Bali 3D/2N',
                    'partners_id' => 2,
                    'destinations' => 'Tanah Lot, Canggu, Berawa',
                    'location' => 'Bali',
                    'type'=>'Private',
                    'duration' =>'2D/1N',
                    'code'=> Str::random(26),
                    'description' => 'The charm of this car. Choose CIMA. It is the pride and pleasure of choosing one vertex.',
                    'itinerary'=> 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'include'=> '1.Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>2. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'additional_info' => '* Minimum 2 orang <br> * valid for domestic guest only',
                    'contract_rate' => rand(1000000, 9000000),
                    'markup' => 5,
                    'qty' => '4',
                    'status' => 'Active',
                    'author_id' => 1,
                    'cover'=> '3.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Pecatu 2D/1N',
                    'partners_id' => 2,
                    'destinations' => 'Uluwatu, Pandawa Beach, Melasti Beach',
                    'location' => 'Bali',
                    'type'=>'Group',
                    'duration' =>'2D/1N',
                    'code'=> Str::random(26),
                    'description' => 'The charm of this car. Choose CIMA. It is the pride and pleasure of choosing one vertex.',
                    'itinerary'=> 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'include'=> '1.Lorem Ipsum is simply dummy text of the printing and typesetting industry.<br>2. Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'additional_info' => '* Minimum 2 orang <br> * valid for domestic guest only',
                    'contract_rate' => rand(1000000, 9000000),
                    'markup' => 5,
                    'qty' => '6',
                    'status' => 'Active',
                    'author_id' => 1,
                    'cover'=> '4.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]
        );      
    }
}

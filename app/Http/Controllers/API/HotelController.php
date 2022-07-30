<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Hotel;
use App\Models\HotelDetail;

class HotelController extends Controller
{
    public function getCities(){
        $cities = City::all();
        foreach($cities as $city){
            $total = Hotel::where('city_id', $city->id)->count();
            $city['total_hotel'] = $total;
        }
        return response([
            'status' => 200,
            'cities' => $cities
        ]);
    }
    public function getHotelList($cityId){
        $cityName = City::where('id',$cityId)->first();
        $hotel_list = Hotel::where('city_id', $cityId)->paginate(5);

        foreach($hotel_list as $hotel_item){
            $hotel_item->company_name = $hotel_item->company->name;
        }
        return response([
            'status' => 200,
            'cityName' => $cityName->name,
            'hotellist' => $hotel_list
        ]);
    }
    public function getHotelDetail($hotelId){
        $hotel = Hotel::where('id',$hotelId)->first();
        $hotel->service = $hotel->hotel_detail->service;
        $hotel->company_name = $hotel->company->name;
        $hotel->thumbnail = json_decode($hotel->hotel_detail->thumbnail);
        $hotelData = [];
        array_push($hotelData, $hotel);
        return response()->json([
            'status' => 200,
            'hotelData' => $hotelData
        ]);
    }
}

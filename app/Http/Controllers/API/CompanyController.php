<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Tour;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class CompanyController extends Controller
{
    //get all types of tour
    public function getTypes(){
        $types = Type::get();
        return response()->json([
            'types' => $types
        ]);
    }
    public function createTour(Request $request){
        $rules = [
            'name' => 'required',
            'local' => 'required',
            'time' => 'required',
            'price' => 'required|integer',
            'desc' => 'required',
            'image' => 'required',

        ];
        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages, [
            'name' => "Name of Tour",
            'local' => "Local",
            'time' => "Time",
            'price' => "Price",
            'desc' => "Description",
            'image' => "Image"
        ]);
        if($validator->fails()){
            return response()->json([
                'validator_errors' => $validator->errors()
            ]);
        } else {
            // Chú thích cho PHP intelephense hiểu biến $user 
            /** @var \App\Models\MyUserModel $user **/
            //get current Company
            $user = auth()->user();
            $tour = new Tour();
            $tour->company_id = $user->company->id;
            $tour->name = $request->input('name');
            $tour->local = $request->input('local');
            $tour->time = $request->input('time');
            $tour->price = $request->input('price');
            $tour->desc = $request->input('desc');
            $tour->city_id = 0;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('upload/tour/', $filename);
                $tour->image = 'upload/tour'.$filename;
            }
            $tour->save();
            $types = json_decode($request->input('types'));
            foreach($types as $type){
                DB::table('tour_type')->insert([
                    'tour_id' => $tour->id,
                    'type_id' => $type
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Add Tour Successfully'
            ]);
        }
    }

    public function totalProduct(){
        // Chú thích cho PHP intelephense hiểu biến $user 
        /** @var \App\Models\MyUserModel $user **/
        //get current Company
        $user = auth()->user();
        $company_id = $user->company->id;
        $total_hotel = Hotel::where('company_id',$company_id)->count();
        return response()->json([
            'status' => 200,
            'total' => $total_hotel
        ]);
    }
}

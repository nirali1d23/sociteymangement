<?php
namespace App\Http\Controllers\API\User;
use App\Models\Bookamenities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AmenitiesController extends Controller
{
    public function requestamenitiesbooking(Request $request)
    {
        $data = Bookamenities::create([
            'user_id' => $request->user_id,
            'amenities_id' => $request->amenities_id,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description
        ]);
        return response([
            'message' => 'Amenities Booked Successfully..!',
            'data' => $data,    
            'statusCode' => 200],200);
    }
    public function cancelbooking(Request $request)
    {
       $data =  Bookamenities::find($request->id);

       if($data)
       {
            $data->status = 3;
            $data->save();

            return response([
                'message' => 'Amenities canceled Successfully..!',
                'data' => $data,    
                'statusCode' => 200],200);
       }

       return response([
        'message' => 'No  Amenities Found..!',
        'data' => $data,    
        'statusCode' => 400],400);
    }
    
}

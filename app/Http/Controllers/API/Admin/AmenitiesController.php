<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Amenities;
use App\Models\Bookamenities;
use App\Traits\ImageUpload;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Symfony\Component\HttpFoundation\File\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AmenitiesController extends Controller
{
    use ImageUpload;
    function generateTimeSlots($startTime, $endTime, $interval = 60) 
    {
     
        // Create Carbon instances for the start and end times
        $start = Carbon::createFromFormat('H:i:s', $startTime);
        $end = Carbon::createFromFormat('H:i:s', $endTime);
    
        // Define the period with the interval
        $period = CarbonPeriod::create($start, "PT{$interval}M", $end); // PT{$interval}M means interval in minutes
    
        $slots = [];
        foreach ($period as $time) {
            $slotStart = $time->format('H:i:s');
            $slotEnd = $time->copy()->addMinutes($interval);
    
            // Ensure the end time of the last slot doesn't exceed the provided end time
            if ($slotEnd->greaterThan($end)) {
                $slotEnd = $end;
            }
    
            $slots[] = "$slotStart - " . $slotEnd->format('H:i:s');
    
            // Stop if the end time matches the provided end time
            if ($slotEnd->equalTo($end)) {
                break;
            }
        }
    
        return $slots;
    
    
  

    }
   
    public function timeslotes()
    {
        
          // Usage
    $startTime = '12:00:00';
    $endTime = '07:15:09';
    $slots = generateTimeSlots($startTime, $endTime, 60); // 60 minutes for 1-hour slots
    print_r($slots);
    }
    public function create(Request $request)
    {
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); 
        }
        if($request->has('time'))
        {
              
        }
        $store = new Amenities;
        $store->amenities_name = $request->amenities_name;
        $store->rule= $request->rule;
        $store->start_time= $request->start_time;
        $store->end_time= $request->end_time;
        $store->image = $image;
        $store->extra_time_status  = 0;



        if($request->has('morning_start_time') && $request->has('morning_end_time')) 
        {
              $store->morning_start_time = $request->morning_start_time;
              $store->morning_end_time = $request->morning_end_time;
              $store->evening_start_time = $request->evening_start_time;
              $store->evening_end_time = $request->evening_end_time;
              $store->extra_time_status  = 1;
        }

        $store->save();

        return response( [
            'message' => 'Amenities Created Successfully..!',
            'statusCode' => 200
        ], 200 );

    }
    public function display(Request $request)
    {
        $data = Amenities::get()->map(function($item)
        {
              if($item->extra_time_status == 1 )
              {

              }
             $item->image = url('image/' . $item->image);
             return $item;
        });
        return response([
           'message' => 'Amenities Displayed Successfully..!',
           'data' => $data,
           'statusCode' => 200
        ],200);
    }
    public function edit(Request $request)
    {

          $store =  Amenities::find($request->id);

           if($store)
           {
            if ($request->hasFile('image')) 
            {
                $image_1 = $request->file('image');
                $image = $this->uploadImage($image_1, 'image'); 
            }

            $store->amenities_name = $request->amenities_name;
            $store->rule= $request->rule;
            $store->image = $image;
            $store->save();

            
        return response( [
            'message' => 'Amenities Updated Successfully..!',
            'statusCode' => 200
        ], 200 );
    

           }

           return response( [
            'message' => 'Amenities Not found..!',
            'statusCode' => 400
        ], 400 );

        
    }
    public function delete(Request $request)
    {
        $store =  Amenities::find($request->id);

        if($store)
        {
             $store->delete();

             $bookings = Bookamenities::where('amenities_id',$request->id)->get();
               if($bookings)
               {
                    foreach ($bookings as $booking) 
                    {
                        $booking->delete();
                    }
               }
             return response( [
                'message' => 'Amenities Deleted Successfully..!',
                'statusCode' => 200
            ], 200 );

        }

        
        return response( [
            'message' => 'Amenities Not found..!',
            'statusCode' => 400
        ], 400 );
    }
}










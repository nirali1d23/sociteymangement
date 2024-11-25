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
    function generateTimeSlots($startTime,$endTime,$interval = 60) 
    {
       
    
        // Create Carbon instances for the start and end times
        $start = Carbon::createFromFormat('H:i:s', $startTime);
        $end = Carbon::createFromFormat('H:i:s', $endTime);
    

        if ($end->lessThan($start)) {

            $end->addDay();
        }
    
        $period = CarbonPeriod::create($start, "PT{$interval}M", $end);
    
        $slots = [];
        foreach ($period as $time) 
        {
            $slotStart = $time->format('H:i:s');
            $slotEnd = $time->copy()->addMinutes($interval); 
    
            if ($slotEnd->greaterThan($end)) {
                $slotEnd = $end;
            }
    
            $slots[] = "$slotStart - " . $slotEnd->format('H:i:s');
    
           
            if ($slotEnd->equalTo($end)) {
                break;
            }
        }
    
        return $slots;
    }
    public function create(Request $request)
    {
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); 
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
    public function display2(Request $request)
    {
        $data = Amenities::with('bookamenities')->get()->map(function($item)
        {
              if($item->extra_time_status == 1 )
              {

                $moring_slots = $this->generateTimeSlots($item->morning_start_time, $item->morning_end_time, 60);
                $evening_slots = $this->generateTimeSlots($item->evening_start_time, $item->evening_end_time, 60);
                $item->morning_time_slots = $moring_slots;

                $item->evening_time_slots = $evening_slots; 
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
    public function display(Request $request)
    {
        $data = Amenities::with('bookamenities')->get()->map(function ($item) {
            if ($item->extra_time_status == 1) {
                // Generate morning and evening slots
                $morning_slots = $this->generateTimeSlots($item->morning_start_time, $item->morning_end_time, 60);
                $evening_slots = $this->generateTimeSlots($item->evening_start_time, $item->evening_end_time, 60);
        
                // Get booked times from the database
                $bookedTimes = $item->bookamenities->map(function ($booking) {
                    return [
                        'start_time' => $booking->start_time, // Assuming 'start_time' is already in 'H:i:s' format
                        'end_time' => $booking->end_time,     // Assuming 'end_time' exists and is in 'H:i:s' format
                    ];
                })->toArray(); // Convert to array for easier manipulation
        
            
                \Log::debug('Booked times: ', $bookedTimes);
        
              
                $isSlotBooked = function ($slotStart, $slotEnd) use ($bookedTimes) {
                    $slotStartTimestamp = strtotime($slotStart);
                    $slotEndTimestamp = strtotime($slotEnd);
        
                    // Debugging: Show slot being checked
                    \Log::debug('Checking slot: ', ['start' => $slotStart, 'end' => $slotEnd]);
        
                    // Loop through all booked times
                    foreach ($bookedTimes as $booking) {
                        $bookingStartTimestamp = strtotime($booking['start_time']);
                        $bookingEndTimestamp = strtotime($booking['end_time']);
        
                        // Debugging: Show booked times being checked
                        \Log::debug('Checking booking: ', ['start' => $booking['start_time'], 'end' => $booking['end_time']]);
        
                        // Check if there is any overlap between the slot and any booking
                        if (
                            ($slotStartTimestamp < $bookingEndTimestamp && $slotEndTimestamp > $bookingStartTimestamp) || // Overlap condition
                            ($slotStartTimestamp >= $bookingStartTimestamp && $slotStartTimestamp < $bookingEndTimestamp) ||
                            ($slotEndTimestamp > $bookingStartTimestamp && $slotEndTimestamp <= $bookingEndTimestamp)
                        ) {
                            return true;  // Slot is booked
                        }
                    }
                    return false;  // Slot is available
                };
        
                // Map morning slots with booked status
                $item->morning_time_slots = array_map(function ($slot) use ($isSlotBooked) {
                    $slotRange = explode(' - ', $slot);
                    $start = $slotRange[0];
                    $end = $slotRange[1];
        
                    $status = !$isSlotBooked($start, $end); // True if slot is available
                    return [
                        'slot' => $slot,
                        'status' => $status,
                    ];
                }, $morning_slots);
        
                // Map evening slots with booked status
                $item->evening_time_slots = array_map(function ($slot) use ($isSlotBooked) {
                    $slotRange = explode(' - ', $slot);
                    $start = $slotRange[0];
                    $end = $slotRange[1];
        
                    $status = !$isSlotBooked($start, $end); // True if slot is available
                    return [
                        'slot' => $slot,
                        'status' => $status,
                    ];
                }, $evening_slots);
            }
        
            // Append full image URL
            $item->image = url('image/' . $item->image);
        
            return $item;
        });
        
        return response([
            'message' => 'Amenities Displayed Successfully!',
            'data' => $data,
            'statusCode' => 200
        ], 200);
        
    
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










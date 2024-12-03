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
    function generateTimeSlotss($startTime,$endTime,$interval = 60) 
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

    public function checkSlotAvailability($slots, $bookedTimes) {
        $availableSlots = [];
    
        foreach ($slots as $slot) {
            // Split the slot into start and end times
            list($slotStart, $slotEnd) = explode(' - ', $slot);
    
            $isBooked = false;
    
            foreach ($bookedTimes as $booking) {
                // Split the booked time into start and end times
                list($bookingStart, $bookingEnd) = explode(' - ', $booking);
    
                // Check if the current slot overlaps with any booked time
                if (
                    ($slotStart >= $bookingStart && $slotStart < $bookingEnd) || 
                    ($slotEnd > $bookingStart && $slotEnd <= $bookingEnd)
                ) {
                    $isBooked = true; // Slot is booked
                    break;
                }
            }
    
            $availableSlots[] = [
                'slot' => $slot,
                'status' => !$isBooked,  // true if available, false if booked
            ];
        }
    
        return $availableSlots;
    }
    
    

  function generateTimeSlots($startTime, $endTime, $interval = 60) 
  {
    $start = Carbon::createFromFormat('H:i:s', $startTime);
    $end = Carbon::createFromFormat('H:i:s', $endTime);

    if ($end->lessThan($start)) {
        $end->addDay();
    }

    $slots = [];
    while ($start->lessThanOrEqualTo($end)) {
        $slotStart = $start->format('H:i:s');
        $slotEnd = $start->copy()->addMinutes($interval)->format('H:i:s');

        if ($start->copy()->addMinutes($interval)->greaterThan($end)) {
            $slotEnd = $end->format('H:i:s');
        }

        $slots[] = "$slotStart - $slotEnd";
        $start->addMinutes($interval);
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

    function updateSlotStatus($amenityId, $timeSlots, $bookedTimes)
{
   
    // Initialize an array for slot statuses
    $slotsWithStatus = [];

    foreach ($timeSlots as $slot) {
        [$slotStart, $slotEnd] = explode(' - ', $slot);

        $isAvailable = true;

        foreach ($bookedTimes as $bookedTime) {
            [$bookedStart, $bookedEnd] = explode(' - ', $bookedTime);

            // Check if the slot overlaps with the booked time
            if (
                ($slotStart >= $bookedStart && $slotStart < $bookedEnd) ||
                ($slotEnd > $bookedStart && $slotEnd <= $bookedEnd) ||
                ($slotStart <= $bookedStart && $slotEnd >= $bookedEnd)
            ) {
                $isAvailable = false;
                break;
            }
        }

        // Append the status with the slot
        $slotsWithStatus[] = [
            'slot' => $slot,
            'status' => $isAvailable ? 'true' : 'false',
        ];
    }

    return $slotsWithStatus;
}

    public function display(Request $request) 
    {
   
    // $date =  $request->date;
    $date = date("y-m-d");


    $data = Amenities::with('bookamenities')->get()->map(function ($item) use ($date) {
        if ($item->extra_time_status == 1) {
            // Generate morning and evening time slots
            $morningSlots = $this->generateTimeSlots($item->morning_start_time, $item->morning_end_time);
            $eveningSlots = $this->generateTimeSlots($item->evening_start_time, $item->evening_end_time);

            // Get booked times from the database
            $bookedTimes = $item->bookamenities->where('date',$date)->map(function ($booking) {
                return $booking->start_time . ' - ' . $booking->end_time;
            })->toArray();
    
            // Update slot status for morning and evening slots
            $item->morning_time_slots = $this->updateSlotStatus($item->id, $morningSlots, $bookedTimes);
            $item->evening_time_slots = $this->updateSlotStatus($item->id, $eveningSlots, $bookedTimes);
        }
    
        // Append the full image URL
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










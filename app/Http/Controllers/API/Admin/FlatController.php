<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Flat;
use App\Models\House;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class FlatController extends Controller
{
    public function create(Request $request)
    {  

       

        // if ($request->has('block')) 
        // {
        //     $data = []; 
        //     foreach ($request->block as $blockData) 
        //     {
              
        //             $no_of_floors = $blockData['Floor_number_To'] - $blockData['Floor_number_from'];
        //             $no_of_house_per_floor = $blockData['no_of_house_per_floor_to'] -  $blockData['no_of_house_per_floor'];
              

        //              $block = Flat::create([

        //               'block_no' => $blockData['block_no']


        //              ]);



        //              for($i=1;$i<=$no_of_floors;$i++)
                    
        //              {
        //                 for($j=1;$j<=$no_of_house_per_floor;$j++)
        //                 {
        //                     $house_number = $i . '0' . $j;

        //                     House::create([
        //                         'house_number' => $house_number,
        //                         'flat_id' =>  $block->id,
        //                     ]);
        //                 }
        //              }
        //     } 
          

        // }
        if ($request->has('block')) {
            foreach ($request->block as $blockData) { // Rename to avoid conflict
                $no_of_floors = $blockData['Floor_number_To'] - $blockData['Floor_number_from'] + 1; // Add 1 to include the range
                $no_of_house_per_floor = $blockData['no_of_house_per_floor_to'] - $blockData['no_of_house_per_floor'] + 1;
        
                // Create the block in the `flats` table
                $block = Flat::create([
                    'block_no' => $blockData['block_no'],
                ]);
        
                // Generate houses for the block
                for ($i = 1; $i <= $no_of_floors; $i++) {
                    for ($j = 1; $j <= $no_of_house_per_floor; $j++) {
                        $house_number = $i . '0' . $j;
        
                        // Create the house with the correct `flat_id`
                        House::create([
                            'house_number' => $house_number,
                            'flat_id' => $block->id, // Correctly associate the house with the block
                        ]);
                    }
                }
            }
        }
        

                return response( [
                    'message' => 'Flat stored.',
                    'statusCode' => 200
                ],200 );

        }

   
        //   for($i=1;$i<=$no_of_floor;$i++)
        //   {
        //      for($j=1;$j<=$no_of_house_per_floor;$j++)
        //      {
        //             $house_number = $i  .'0'. $j;

        //             echo $house_number."\n";
        //      }
        //   }
            // echo $house_number;
           
        //  if($request->type == '0')
        //  {
        //       $flat->flat_number = $request->flat_number;
        //       $flat->floor_number = $request->floor_number;
        //       $flat->block_number = $request->block_number;
        //  }

        //   elseif($request->type == '1')
        //   {
        //      $flat->house_no = $request->house_no;
        //   }

        // $save =  $flat->save();

        // if($save)
        // {
        //     return response( [
        //         'message' => 'Flat stored.',
        //         'statusCode' => 200
        //     ],200 );
        // }

        // return response( [
        //     'message' => 'Failed to store flat..!',
        //     'statusCode' => 400
        // ],400 );
        
}

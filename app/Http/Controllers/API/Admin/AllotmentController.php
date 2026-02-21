<?php
namespace App\Http\Controllers\API\Admin;
use App\Models\Allotment;
use App\Models\Flat;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AllotmentController extends Controller
{
    public function block_list(Request $request)
    { 
         $data = Flat::orderBy('created_at','asc')->get();
         if($data!=null)
         {
            return response( [
                'message' => 'Blcok list show Successfullyy',
                'data' => $data,
                'statusCode' => 200
            ],200);
         }
         return response( [
            'message' => 'no block found',
            'statusCode' => 404
        ],404);

    }
    public function base64Image(Request $request)
    {
        
        if ($request->has("file"))
        {
            $base64Image = $request->file;


            if 
            (
                preg_match("/^data:image\/(\w+);base64,/", $base64Image, $type)
            ) 
            {
                $base64Image = substr(
                    $base64Image,
                    strpos($base64Image, ",") + 1
                );
                $type = strtolower($type[1]); 
            } else {
                return response()->json([
                    "data" => [],
                    "message" => "Invalid base64 string",
                    "status" => 400,
                ]);
            }

            $base64Image = str_replace(" ", "+", $base64Image);
            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                return response()->json([
                    "data" => [],
                    "message" => "Base64 decode failed",
                    "status" => 400,
                ]);
            }


            $imageName = uniqid() . '.' . 'png';


            $filePath = public_path("images/") . $imageName;

            if (file_put_contents($filePath, $imageData)) 
            {



                $imageUrl = url("images/    " . $imageName);
                return response()->json([   
                    "data" => $imageName,
                    "message" => "Image uploaded successfully",
                    "status" => 200,
                ]);
            }
             else {
                return response()->json([
                    "data" => [],
                    "message" => "Failed to save image",
                    "status" => 500,
                ]);
            }
        } else {
            return response()->json([
                "data" => [],
                "message" => "File not found in the request",
                "status" => 400,
            ]);
        }

      
    }    
    public function houselist(Request $request)
{
    $flat = Flat::find($request->flat_id);

    if (!$flat) {
        return response([
            'message' => 'no house found',
            'statusCode' => 404
        ], 404);
    }

    // Get all houses of the flat
    $housesQuery = $flat->houses();

    // If admin → remove houses already in allotment table
    if ($request->type === 'admin') {
        $housesQuery->whereNotIn('id', function ($query) {
            $query->select('flat_id')->from('allotments');
        });
    }

    $houses = $housesQuery->get();

    return response([
        'message' => 'House list show Successfully',
        'data' => $houses,
        'statusCode' => 200
    ], 200);
    }
    public function store(Request $request)
    {
         $allotment = new  Allotment;
         $allotment->user_id = $request->user_id; 
         $allotment->flat_id = $request->flat_id; 
         $allotment->save();
         return response( [
            'message' => 'User alloteted  Successfully',
     
            'statusCode' => 200
        ],200);


    }
    public function userlist(Request $request)
    {
        //  $data = User::where('user_type','2')->with(['allotment.flat.block'])->get();
        $data = User::where('user_type', '2')
            ->with([
                'allotment.flat' => function ($query) {
                    $query->select('id', 'house_number', 'flat_id');
                },
                'allotment.flat.block' => function ($query) {
                    $query->select('id', 'block_no'); 
                }
            ])
            ->get();
                if($data!=null)
                {
                    return response( [
                        'message' => 'User list show Successfully',
                        'data' => $data,
                        'statusCode' => 200
                    ],200);
                }


    }
    public function delteuser(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
           ]);


           $data = User::find($request->user_id);


          

    }
}

<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Polloptions;
use App\Models\Pollquestion;
use App\Models\Pollsurvey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function create(Request $request)
    {
        $store = new  Pollquestion;
        $store->question = $request->question;
      $store->save();

      $last_id = $store->id;
        foreach($request->option as $optioons)
        {
        $store_option = new Polloptions;
        $store_option->question_id = $last_id;
        $store_option->option = $optioons;
        $store_option->save();
        }

        return response([
            'message' => 'Poll Created Successfully..!',
            'statusCode' => 200
        ],200 );

    }
    public function display(Request $request)
    {
        $data = Pollquestion::with(['polloption' => function($query) {
            $query->withCount('pollsurvey');
        }])->get();
        // $data = Pollquestion::with('polloption')->get();
   
        if($data!=null)
        {
        return response([
            'message' => 'Poll Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
        }
    } 
    public function polldetails(Request $request)
    {
        $pollQuestionId = $request->pollQuestionId; 
         $data = Pollquestion::with(['polloption' => function ($query) {
            $query->withCount(['pollsurvey as survey_count' => function ($q) {
                $q->select(\DB::raw('count(*)'));
            }])->with(['pollsurvey' => function ($q) {
                $q->with('user:id,name');
            }]);
        }])->find($pollQuestionId);
    
        if ($data) {
            return response([
                'message' => 'Poll Displayed Successfully..!',
                'data' => $data,
                'statusCode' => 200
            ], 200);
        } else {
            return response([
                'message' => 'Poll Question Not Found',
                'statusCode' => 404
            ], 404);
        }

        $pollQuestionId = $request->pollQuestionId;
            //     $data = Pollquestion::with(['polloption' => function($query) use ($pollQuestionId) {
            //         $query->whereHas('pollsurvey', function($q) use ($pollQuestionId) {
            //             $q->where('question_id', $pollQuestionId);
            //         })->with(['pollsurvey' => function($q) {
            //             $q->with('user:id,name'); // Assuming the User model has an 'id' and 'name' field
            //         }]);
            //     }])->find($pollQuestionId); // Fetch only the given poll question
            
            // if ($data) {
            //     return response([
            //         'message' => 'Poll Displayed Successfully..!',
            //         'data' => $data,
            //         'statusCode' => 200
            //     ], 200);
            // } else {
            //     return response([
            //         'message' => 'Poll Question Not Found',
            //         'statusCode' => 404
            //     ], 404);
            // }

    
    }
}

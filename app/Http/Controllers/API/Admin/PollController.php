<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Polloptions;
use App\Models\Pollquestion;
use App\Models\Pollsurvey;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotificationTrait;

class PollController extends Controller
{

    use FirebaseNotificationTrait;

    public function create(Request $request)
    {
        $store = new  Pollquestion;
        $store->question = $request->question;
      $store->save();

      $data = User::all();
      foreach($data as  $token)
      {
          if($token->fcm_token !=null)
          {
              $fcmToken = $token->fcm_token;
              $title = "🗳️ New Poll Created!";
              $body = "📢 Have your say! A new poll is now live. Cast your vote and let your opinion be heard. 🌟";
               $this->sendFirebaseNotification($fcmToken, $title, $body);
          }
      }

           
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
       
        $data = Pollquestion::with(['polloption' => function($query) 
        {
            $query->withCount('pollsurvey');
        }
        ])->get()->map(function($question) 
        {
            $totalScore = $question->polloption->sum('pollsurvey_count');
            
          
            $question->total_score = $totalScore;

   
            if($request->has('user_id')) 
            {
                $userId = $request->input('user_id');
                $userHasResponded = false;
        
                foreach ($question->polloption as $option) {
                    if ($option->pollsurvey->contains('user_id', $userId)) {
                        $userHasResponded = true;
                        break; // Exit the loop as soon as a match is found
                    }
                }
        
                $question->status = $userHasResponded ? 1 : 0;
            } else {
                $question->status = 0; // Default status if no user_id is provided
            }
            
            return $question;
        });
    
            if ($data->isNotEmpty()) {
                return response([
                    'message' => 'Poll Displayed Successfully..!',
                    'data' => $data,
                    'statusCode' => 200
                ], 200);
            } else {
                return response([
                    'message' => 'No Polls Found',
                    'data' => [],
                    'statusCode' => 404
                ], 404);
            }
    
    } 
    public function polldetails(Request $request)
    {
        // $pollQuestionId = $request->pollQuestionId; 
        //  $data = Pollquestion::with(['polloption' => function ($query) {
        //     $query->withCount(['pollsurvey as survey_count' => function ($q) {
        //         $q->select(\DB::raw('count(*)'));
        //     }])->with(['pollsurvey' => function ($q) {
        //         $q->with('user:id,name');
        //     }]);
        // }])->find($pollQuestionId);
    
        $pollQuestionId = $request->pollQuestionId;

        $data = Pollquestion::with(['polloption' => function ($query) {
            $query->withCount(['pollsurvey as survey_count' => function ($q) {
                $q->select(\DB::raw('count(*)'));
            }])->with(['pollsurvey' => function ($q) {
                $q->with('user:id,name');
            }]);
        }])
        ->withCount(['pollsurvey as total_votes' => function ($query) use ($pollQuestionId) {
            $query->where('poll_question_id', $pollQuestionId);
        }])
        ->find($pollQuestionId);
        

    
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

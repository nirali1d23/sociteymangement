<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Polloptions;
use App\Models\Pollquestion;
use App\Models\Pollsurvey;
use App\Models\User;

class PollController extends Controller
{
    public function submitpoll(Request $request)
    {
         $data = new  Pollsurvey;

         $data->poll_question_id = $request->poll_question_id;
         $data->poll_option_id	 = $request->poll_option_id;
         $data->user_id = $request->user_id;

         $data->save();


         return response([
            'message' => 'Poll Submited Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );

    }
}

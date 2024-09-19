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
        $data = Pollquestion::with('polloption')->with('pollsurvey')->get();
        return response([
            'message' => 'Poll Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
    } 
}

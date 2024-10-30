<?php

namespace App\Http\Controllers\API\User;
use App\Models\NoticeComment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
     public function notice_comment(Request $request)
     {
        $data =  new NoticeComment;
        $data->user_id = $request->user_id;
        $data->notice_id = $request->notice_id;
        $data->comment = $request->comment;

        $data->save();

        return response([
            'message' => 'Notice Comment Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
     }
}

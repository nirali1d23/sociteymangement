<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pollquestion;
use App\Models\Polloptions;
use App\Models\User;
use DataTables;
use App\Traits\FirebaseNotificationTrait;

class PollController extends Controller
{
        use FirebaseNotificationTrait;

    public function index()
    {
        return view('admin_panel.admin.poll');
    }

    public function data()
    {
        return DataTables::of(
            Pollquestion::withCount([
                'polloption as options_count',
                'pollsurvey as votes_count'
            ])
        )
        ->addColumn('action', function ($row) {
            return '
                <button class="btn btn-danger btn-sm deletePoll"
                    data-id="'.$row->id.'">Delete</button>
            ';
        })
        ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'option'   => 'required|array|min:2'
        ]);

        $poll = Pollquestion::create([
            'question' => $request->question
        ]);

        foreach ($request->option as $opt) {
            Polloptions::create([
                'question_id' => $poll->id,
                'option' => $opt
            ]);
        }


         $data = User::where('user_type',2)->get();
      foreach($data as  $token)
      {
          if($token->fcm_token !=null)
          {
              $fcmToken = $token->fcm_token;
              $title = "🗳️ New Poll Created!";
              $body = "📢 Have your say! A new poll is now live. Cast your vote and let your opinion be heard. 🌟";
               $this->sendFirebaseStaffNotification($fcmToken, $title, $body);
          }
      }

        return response()->json([
            'success' => true,
            'message' => 'Poll created successfully'
        ]);
    }

    public function delete($id)
    {
        Polloptions::where('question_id', $id)->delete();
        Pollquestion::where('id', $id)->delete();

        return response()->json(['success' => true]);
    }
}

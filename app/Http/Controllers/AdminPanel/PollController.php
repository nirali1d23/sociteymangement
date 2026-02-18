<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pollquestion;
use App\Models\Polloptions;
use App\Models\User;
use App\Traits\FirebaseNotificationTrait;

class PollController extends Controller
{
    use FirebaseNotificationTrait;

    // PAGE
    public function index()
    {
        return view('admin_panel.poll.index');
    }

    // DATATABLE LIST
    public function list()
    {
        $polls = Pollquestion::withCount([
            'polloption as options_count',
            'pollsurvey as votes_count'
        ])->latest();

        return datatables()->of($polls)
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-danger btn-sm deletePoll" data-id="'.$row->id.'">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // STORE POLL
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'option'   => 'required|array|min:2'
        ]);

        $question = Pollquestion::create([
            'question' => $request->question
        ]);

        foreach ($request->option as $opt) {
            Polloptions::create([
                'question_id' => $question->id,
                'option' => $opt
            ]);
        }

        // Notify staff
        $users = User::where('user_type', 2)->whereNotNull('fcm_token')->get();
        foreach ($users as $user) {
            $this->sendFirebaseStaffNotification(
                $user->fcm_token,
                "🗳️ New Poll Created!",
                "📢 A new poll is live. Please vote!"
            );
        }

        return response()->json(['success' => true]);
    }

    // DELETE
    public function destroy($id)
    {
        Pollquestion::where('id', $id)->delete();
        Polloptions::where('question_id', $id)->delete();

        return response()->json(['success' => true]);
    }
}

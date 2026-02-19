<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Flat;
use App\Models\User;
use App\Models\Notice;
use App\Models\House;
use App\Models\Amenities;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin_panel.admin.dashboard', [
            'totalBlocks'    => Flat::count(),
            'totalHouses'    => House::count(),
            'totalResidents' => User::where('user_type', 2)->count(),
            'totalStaff'     => User::where('user_type', 3)->count(),
            'totalNotices'   => Notice::count(),
            'totalAmenities' => Amenities::count(),
        ]);
    }
}

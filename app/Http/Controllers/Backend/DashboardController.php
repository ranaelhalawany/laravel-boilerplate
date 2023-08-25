<?php

namespace App\Http\Controllers\Backend;
use App\Domains\Auth\Models\User;


/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $totalUsers = User::count();
        $userTypes = User::select('type', \DB::raw('COUNT(*) as count'))
                    ->groupBy('type')
                    ->get();
    

                    $userRegistrations = User::selectRaw('DATE(created_at) as registration_date, COUNT(*) as count')
                    ->groupBy('registration_date')
                    ->get();
        
       return view('backend.dashboard', [
        'totalUsers' => $totalUsers,
        'userTypes' => $userTypes,
        'userRegistrations' => $userRegistrations,
    ]);

    }
}
<?php

namespace App\Http\Controllers\Backend;
use App\Domains\Auth\Models\User;
//use Charts;
//use \ConsoleTVs\Charts\Facades\Charts;


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
        // $userTypesChart = \ConsoleTVs\Charts\Facades\Charts ::database($userTypes, 'pie', 'highcharts')
        // ->title('User Types')
        // ->groupBy('type');
       return view('backend.dashboard', [
        'totalUsers' => $totalUsers,
        'userTypes' => $userTypes,
        'userRegistrations' => $userRegistrations,
    ]);

    }
}
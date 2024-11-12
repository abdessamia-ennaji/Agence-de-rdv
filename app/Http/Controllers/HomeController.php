<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Rdv;
use App\Models\RdvStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 1) {
                // Admin user - redirect to admin dashboard

                // Get today's date
                $today = Carbon::today();

                // Get count of today's appointments
                $todaysRdvCount = Rdv::whereDate('rdv_date', $today)->count();

                // Get the total count of appointments
                $totalRdvs = Rdv::count();

                /////////// COUNT THE ELEMENTS BY STATUS /////////////////////
                $enAttenteCount = DB::table('rdv_statuses')
                    ->where('status', 'En attente')
                    ->count();

                $ConfirmeCount = DB::table('rdv_statuses')
                    ->where('status', 'Confirmé')
                    ->count();

                $AnnuleCount = DB::table('rdv_statuses')
                    ->where('status', 'Annulé')
                    ->count();

                // Get the 5 most recent appointments
                $recentRdvs = Rdv::orderBy('rdv_date', 'desc')->limit(5)->get();

                // Get all RdvStatuses
                $rdvStatuses = RdvStatus::all();

                // Get appointments grouped by day (Mon-Sat)
                $startOfWeek = Carbon::now()->startOfWeek(); // Start of this week (Monday)
                $endOfWeek = Carbon::now()->endOfWeek(); // End of this week (Saturday)

                // Get appointments for the current week (Mon-Sat)
                $appointments = Rdv::with('status')
                    ->whereBetween('rdv_date', [$startOfWeek, $endOfWeek])
                    ->get();

                // Initialize status counts for each day of the week (Mon-Sat)
                $statusCounts = [
                    'Confirmé' => [0, 0, 0, 0, 0, 0],
                    'En attente' => [0, 0, 0, 0, 0, 0],
                    'Annulé' => [0, 0, 0, 0, 0, 0],
                ];

                // Iterate over appointments to count them by day of the week and status
                foreach ($appointments as $rdv) {
                    // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                    $dayOfWeek = Carbon::parse($rdv->rdv_date)->dayOfWeek;
                    $dayIndex = ($dayOfWeek == 0) ? 6 : $dayOfWeek - 1; // Adjust for Sunday to Saturday (Mon-Sat)

                    // Increment the count for the appropriate status
                    $status = $rdv->status ? $rdv->status->status : null;

                    if ($status && isset($statusCounts[$status])) {
                        $statusCounts[$status][$dayIndex]++;
                    }
                }

                // Return view with data
                return view('admin.dashboard', [
                    'todaysRdvCount' => $todaysRdvCount,
                    'totalRdvs' => $totalRdvs,
                    'enAttenteCount' => $enAttenteCount,
                    'ConfirmeCount' => $ConfirmeCount,
                    'AnnuleCount' => $AnnuleCount,
                    'recentRdvs' => $recentRdvs,
                    'rdvStatuses' => $rdvStatuses,
                    'appointments' => $statusCounts, // Pass the status count for the chart
                ]);
            } else {
                // Regular user - redirect to user home page
                return view('users.home');
            }
        } else {
            // Redirect to login if not authenticated
            return redirect()->route('login');
        }
    }
}

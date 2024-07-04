<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employer;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Configuration;

class AppController extends Controller
{
    public function index(){
        $totalDepartements = Departement::all()->count();
        $totalEmployers = Employer::all()->count();
        $totalAdministrateurs = User::all()->count();

        $defaultPaymentDate = null;
        $paymentNotification = "";

        $currentDate = Carbon::now()->day;
        
        $defaultPaymentDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
        
        if($defaultPaymentDateQuery){
            $defaultPaymentDate = $defaultPaymentDateQuery->value;
            $convertedPaymentDate = intval($defaultPaymentDate);

            if($currentDate < $convertedPaymentDate){
             $paymentNotification = "Le payement doit avoir lieu le ".$defaultPaymentDate." de ce mois";
            } else {
                $nextMonth = Carbon::now()->addMonth();
                $nextMonthName = $nextMonth->format('F');

                $paymentNotification = "Le payement doit avoir lieu le ".$defaultPaymentDate." du mois de ".$nextMonthName;
            }
        }
        //$appName = Configuration::where('type','APP_NAME')->first();
        return view('dashboard', compact('totalDepartements', 'totalEmployers', 'totalAdministrateurs','paymentNotification'));
    }
}


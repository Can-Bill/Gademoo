<?php

namespace App\Http\Controllers;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Employer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class PaymentController extends Controller
{


    
    public function index(){

        //$defaultPaymentDate = null;
        $defaultPaymentDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
        // Vérifie si la requête a retourné un résultat
        //if($defaultPaymentDateQuery) {
            $defaultPaymentDate = $defaultPaymentDateQuery->value;
            $convertedPaymentDate = intval($defaultPaymentDate);
            $today = date('d');
    
            $isPaymentDay = false;
    
            if($today == $convertedPaymentDate){
                $isPaymentDay = true;
            }
            
            
            $payments = Payment::latest()->orderBy('id','desc')->paginate(10);
            return view('paiements.index', compact('payments','isPaymentDay'));
     //   }
    }

    public function effectuerPaiements() {
        // Vérifier que nous sommes à la date de paiement avant d'exécuter le code ci-dessous
        $defaultPaymentDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
        if ($defaultPaymentDateQuery) {
            $defaultPaymentDate = intval($defaultPaymentDateQuery->value);
            $today = intval(date('d'));
            
            if ($today !== $defaultPaymentDate) {
                return redirect()->back()->with('error_message', 'Aujourd\'hui n\'est pas la date de paiement. Les paiements ne peuvent être effectués que le ' . $defaultPaymentDate . ' de chaque mois.');
            }
        } else {
            return redirect()->back()->with('error_message', 'La date de paiement par défaut n\'est pas configurée.');
        }
    
        // Tableau de mappage des mois en anglais vers les mois français
        $monthMapping = [
            'JANUARY' => 'JANVIER',
            'FEBRUARY' => 'FEVRIER',
            'MARCH' => 'MARS',
            'APRIL' => 'AVRIL',
            'MAY' => 'MAI',
            'JUNE' => 'JUIN',
            'JULY' => 'JUILLET',
            'AUGUST' => 'AOUT',
            'SEPTEMBER' => 'SEPTEMBRE',
            'OCTOBER' => 'OCTOBRE',
            'NOVEMBER' => 'NOVEMBRE',
            'DECEMBER' => 'DECEMBRE'
        ];
    
        $currentMonth = strtoupper(Carbon::now()->formatLocalized('%B'));
        
        // Récupération du mois en français
        $currentMonthInFrench = $monthMapping[$currentMonth] ?? '';
        // Récupération de l'année en cours
        $currentYear = Carbon::now()->format('Y');
        
        // Simuler des paiements pour les employés dans le mois en cours. Les paiements concernent les employés qui n'ont pas encore été payés dans le mois actuel
        
        // Récupérer la liste des employeurs qui n'ont pas encore été payés pour le mois en cours
        $employers = Employer::whereDoesntHave('payments', function($query) use ($currentMonthInFrench, $currentYear) {
            $query->where('month', '=', $currentMonthInFrench)
                  ->where('year', '=', $currentYear);
        })->get();
        
        if ($employers->count() === 0) {
            return redirect()->back()->with('error_message', 'Tous vos employeurs ont déjà été payés pour ce mois ' . $currentMonthInFrench);
        }
    
        // Faire ces paiements pour ces employeurs
        foreach ($employers as $employer) {
            $aEtePayer = $employer->payments()->where('month', '=', $currentMonthInFrench)
                                               ->where('year', '=', $currentYear)
                                               ->exists();
    
            if (!$aEtePayer) {
                $salaire = $employer->montant_journalier * 31;
                
                $payment = new Payment([
                    'reference' => strtoupper(Str::random(10)),
                    'employer_id' => $employer->id,
                    'amount' => $salaire,
                    'launch_date' => now(),
                    'done_time' => now(),
                    'status' => 'SUCCESS',
                    'month' => $currentMonthInFrench,
                    'year' => $currentYear
                ]);
    
                $payment->save();
            }
        }
    
        // Rediriger
        return redirect()->back()->with('success_message', 'Paiement des employeurs effectué pour le mois de ' . $currentMonthInFrench);
    }
    
    
    public function downloadInvoice(Payment $payment) {
        // Vérifier que nous sommes à la date de paiement avant d'exécuter le code ci-dessous
        $defaultPaymentDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
        if ($defaultPaymentDateQuery) {
            $defaultPaymentDate = intval($defaultPaymentDateQuery->value);
            $today = intval(date('d'));
            
            if ($today !== $defaultPaymentDate) {
                return redirect()->back()->with('error_message', 'Les factures ne peuvent être téléchargées que le ' . $defaultPaymentDate . ' de chaque mois.');
            }
        } else {
            return redirect()->back()->with('error_message', 'La date de paiement par défaut n\'est pas configurée.');
        }
    
        try {
            $fullPaymentInfo = Payment::with('employer')->find($payment->id);
            
            // Générer le PDF
            $pdf = PDF::loadView('paiements.facture', compact('fullPaymentInfo'));
            return $pdf->download('facture_' . $fullPaymentInfo->employer->nom . '.pdf');
        } catch (Exception $e) {
            throw new Exception("Une erreur est survenue lors du téléchargement de la facture");
        }
    }
    
    
}



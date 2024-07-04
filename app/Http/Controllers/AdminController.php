<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\storeAdminRequest;
use App\Http\Requests\updateAdminRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\submitDefineAccessRequest;
use Illuminate\Database\Console\Migrations\ResetCommand;
use App\Notifications\SendEmailToAdminAfterRegistrationNotification;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{   
    public function index(){

        $admins = User::paginate(10);
        return view('admins/index', compact('admins'));
    }

    public function create(){
        return view('admins/create');
    }

    public function edit(User $user){
        return view ('admins/edit', compact('user'));
    }

    public function store(storeAdminRequest $request){
        try{
            
            //logique de la creation du compte administrateur

            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('default');
            $user->save();

            //Envoyer un email pour que l'utilisateur confirme son compte

            //Envoyer un code par email pour vérification
            if($user){
                try{

                    ResetCodePassword::where('email', $user->email)->delete();
                    $code = rand(1000, 4000);

                    $data = [
                        'code'=>$code,
                        'email'=>$user->email
                    ];

                    ResetCodePassword::create($data);
                    Notification::route('mail',$user->email)->notify(new SendEmailToAdminAfterRegistrationNotification($code,$user->email));
                    //rediriger l'utilisateur vers une URL
                    return redirect()->route('administrateurs')->with('success_message','Administrateur ajouté'); 
                }catch(Exception $e){
                    dd($e);
                    throw new Exception ('Une erreur est survenue lors de l\'envoi du mail');
                }
                
            }
            
            
        }catch(Exception $e){
            dd($e);
            // throw new Exception('Une erreur est survenue lors de la création de cet administrateur');
        }
    }

    public function update(updateAdminRequest $request, User $user){
        try{
            //logique de mise a jour des informations de l'utilisateur

        }catch(Exception $e){
            //dd($e);
            throw new Exception('Une erreur est survenue lors de la mise a jour des informations de l\'utilisateur');
        }
    }

    public function delete(User $user){
        try{
            //logique de suppression
            try{

                $connectedAdminId = Auth::user()->id;
                if($connectedAdminId !== $user->id){
                    $user->delete();
                    return redirect()->back()->with('success_message', 'L\'administrateur a été retiré');
                }else{
                    return redirect()->back()->with('error_message','Vous ne pouvez pas supprimer votre propre compte');
                }
            }catch(Exception $e){
                dd($e);
            }

        }catch(Exception $e){
           // dd($e);
            throw new Exception('Une erreur est survenue lors de la suppression de l\'admin');
        }
    }

    public function defineAccess($email){

        $checkUserExist = User::where('email', $email)->first();
        if($checkUserExist){
            return view('auth.validate-account', compact('email'));
        }else{
            //Rediriger sur une route 404
            //return redirect()->route('login');
        };
    }

    public function submitDefineAccess(submitDefineAccessRequest $request){
        try{
            $user = User::where('email', $request->email)->first();
            if($user){
                $user->password = Hash::make($request->password);
                $user->email_verified_at = Carbon::now();
                $user->update();
                // si la mise a jour s'est faite correctement
                if($user){
                    $existingCode = ResetCodePassword::where('email',$user->email)->count();

                    if($existingCode >= 1){
                        ResetCodePassword::where('email',$user->email)->delete();
                    }
                }

                return redirect()->route('login')->with('success_message', 'Vos acces ont été correctement défini');
            }else{
                //404
            }
        }catch(Exception $e){  
            dd($e);

        }
    }
}

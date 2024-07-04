<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use Exception;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(){

        $leaves = Leave::paginate(10);
        return view('leave.index', compact('leaves'));

    }

    public function create(){
        $leaves = Leave::all();
        return view('leave.create', compact('leaves'));
    }

   public function store(saveLeaveRequest $request){
        try{

            $query = Leave::create($request->all());

        if($query){
            return redirect()->route('leave.index')->with('success_message', 'Congé ajouté');
        }
        }catch (Exception $e){
            dd($e);
        }
    }

    public function edit(Leave $leave){

        return view ('leave.edit', compact('leave'));
    }


    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        try{
            $leave->nom = $request->nom;
            $leave->type_conge = $request->type_conge ;
            $leave->date_de_depart = $request->date_de_depart;
            $leave->date_de_fin = $request->date_de_fin;
            $leave->description = $request->description;

            $leave->update();

            return redirect()->route('leave.index')->with('success_message', 'Les informations sur le congé ont été mise à jour');
        }catch (Exception $e){
            dd($e);
        }
    }

        public function delete(Leave $leave){
            try{
                $leave->delete();

                return redirect()->route('leave.index')->with('success_message','Congé retirer');
            }catch(Exception $e){
                dd($e);
            }
        }
}

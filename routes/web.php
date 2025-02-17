<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//CREATION DE LA ROUTE LOGIN
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'handleLogin'])->name('handleLogin');
Route::get('/validate-account/{email}', [AdminController::class, 'defineAccess']);
Route::post('/validate-account/{email}', [AdminController::class, 'SubmitDefineAccess'])->name('submitDefineAccess');


Route::prefix('departements')->group(function (){
    Route::get('/', [DepartementController::class, 'index'])->name('departement.index');
    Route::get('/create', [DepartementController::class, 'create'])->name('departement.create');
    Route::post('/create', [DepartementController::class, 'store'])->name('departement.store');
    Route::get('/edit/{departement}', [DepartementController::class, 'edit'])->name('departement.edit');
    Route::put('/update/{departement}', [DepartementController::class, 'update'])->name('departement.update');
    Route::get('/{departement}', [DepartementController::class, 'delete'])->name('departement.delete');

    Route::get('dashboard', [AppController::class, 'index'])->name('dashboard');

});



//Route pour les congés des employés

Route::prefix('leaves')->group(function (){
    Route::get('/', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/create', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/edit/{leave}', [LeaveController::class, 'edit'])->name('leave.edit');
    Route::put('/update/{leave}', [LeaveController::class, 'update'])->name('leave.update');
    Route::get('/{leave}', [LeaveController::class, 'delete'])->name('leave.delete');

});


//Route sécurisée
Route::prefix('employers')->group(function (){
    Route::get('/', [EmployerController::class, 'index'])->name('employer.index');
    Route::get('/create', [EmployerController::class, 'create'])->name('employer.create');
    Route::get('/edit/{employer}', [EmployerController::class, 'edit'])->name('employer.edit');

    //Routes d'actions

    Route::post('/store', [EmployerController::class,'store'])->name('employe.store');
    Route::get('dashboard', [AppController::class, 'index'])->name('dashboard');
    Route::get('/delete/{employer}', [EmployerController::class, 'delete'])->name('employer.delete');

    Route::put('/update/{employer}', [EmployerController::class, 'update'])->name('employe.update');

});


Route::prefix('configurations')->group(function(){
    Route::get('/', [ConfigurationController::class, 'index'])->name('configurations');
    Route::get('/create', [ConfigurationController::class, 'create'])->name('configurations.create');




    //Route d'actions
    Route::post('/store', [ConfigurationController::class, 'store'])->name('configurations.store');
    Route::get('/delete/{configuration}', [ConfigurationController::class, 'delete'])->name('configurations.delete');

    Route::prefix('administrateurs')->group(function (){
        
        Route::get('/', [AdminController::class, 'index'])->name('administrateurs');

        Route::get('/create', [AdminController::class, 'create'])->name('administrateurs.create');

        Route::post('/create', [AdminController::class, 'store'])->name('administrateurs.store');
        
        Route::get('/delete/{user}',[AdminController::class, 'delete'])->name('administrateurs.delete');
    });

    Route::prefix('payment')->group(function(){
        Route::get('/',[PaymentController::class, 'index'])->name('payments');
        Route::get('/init', [PaymentController::class, 'effectuerPaiements'])->name('payments.init');
        Route::get('/download-invoice/{payment}', [PaymentController::class, 'downloadInvoice'])->name('payment.download');
    });

});

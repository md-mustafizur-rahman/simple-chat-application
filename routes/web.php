<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\MessageSent;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/broadcast-message', function (Request $request) {
        $user = Auth::user();
        $message = $request->input('message');
        $timestamp = Carbon::now()->format('Y-m-d H:i:s'); // Current timestamp
    
        // Broadcast the message to the 'chat' channel
        broadcast(new MessageSent($user->name, $message, $timestamp));
    
        return response()->json(['status' => 'Message broadcasted successfully!']);
    });
});

require __DIR__.'/auth.php';

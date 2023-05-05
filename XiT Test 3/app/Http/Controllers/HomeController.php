<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      $this->middleware('auth');
   }

   /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */

   public function index()
   {
      return view('home');
   }

   public function admin_home()
   {
      $users = User::where('role', 'user')->get();

      return view('admin_home', compact('users'));
   }

   public function verification_pending()
   {
      return view('verification_pending');
   }

   public function toggle_verification_ajax(Request $request)
   {
      $user = User::findOrFail($request->user_id);
      $user->approved_by_admin = !$user->approved_by_admin;
      $user->save();

      return response()->json(['status' => 'success', 'verification_status' => $user->approved_by_admin]);
   }
}

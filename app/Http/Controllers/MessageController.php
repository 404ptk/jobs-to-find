<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MessageController extends Controller
{
  /**
   * Display a listing of the messages.
   */
  public function index()
  {
    return view('messages.index');
  }

  /**
   * Get partial view for message modal.
   */
  public function partial($userId)
  {
    $user = User::findOrFail($userId);
    return view('messages.partial', compact('user'));
  }
}

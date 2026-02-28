<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
  /**
   * Display a listing of the messages.
   */
  public function index()
  {
    return view('messages.index');
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
  /**
   * Display a listing of the messages.
   */
  public function index()
  {
    $userId = Auth::id();

    $conversations = Message::where('sender_id', $userId)
      ->orWhere('receiver_id', $userId)
      ->select(\DB::raw('CASE WHEN sender_id = ' . $userId . ' THEN receiver_id ELSE sender_id END as contact_id'), \DB::raw('MAX(created_at) as latest_message_time'))
      ->groupBy('contact_id')
      ->orderBy('latest_message_time', 'desc')
      ->get();

    $contacts = $conversations->map(function ($conv) use ($userId) {
      $contact = User::find($conv->contact_id);
      $latestMessage = Message::where(function ($q) use ($userId, $conv) {
        $q->where('sender_id', $userId)->where('receiver_id', $conv->contact_id);
      })->orWhere(function ($q) use ($userId, $conv) {
        $q->where('sender_id', $conv->contact_id)->where('receiver_id', $userId);
      })->latest()->first();

      $unreadCount = Message::where('sender_id', $conv->contact_id)
        ->where('receiver_id', $userId)
        ->whereNull('read_at')
        ->count();

      return [
        'user' => $contact,
        'latest_message' => $latestMessage,
        'unread_count' => $unreadCount,
      ];
    });

    return view('messages.index', compact('contacts'));
  }

  public function partial($userId)
  {
    $user = User::findOrFail($userId);
    return view('messages.partial', compact('user'));
  }

  /**
   * Store a newly created message.
   */
  public function store(Request $request)
  {
    $request->validate([
      'receiver_id' => 'required|exists:users,id',
      'content' => 'required|string',
    ]);

    $message = Message::create([
      'sender_id' => Auth::id(),
      'receiver_id' => $request->input('receiver_id'),
      'content' => $request->input('content'),
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'Message sent successfully!',
      'data' => $message
    ]);
  }

  /**
   * Get conversation history between current user and specified user.
   */
  public function conversation($userId)
  {
    $currentUserId = Auth::id();
    $otherUser = User::findOrFail($userId);

    $messages = Message::where(function ($query) use ($currentUserId, $userId) {
      $query->where('sender_id', $currentUserId)
        ->where('receiver_id', $userId);
    })
      ->orWhere(function ($query) use ($currentUserId, $userId) {
        $query->where('sender_id', $userId)
          ->where('receiver_id', $currentUserId);
      })
      ->orderBy('created_at', 'asc')
      ->get();

    Message::where('sender_id', $userId)
      ->where('receiver_id', $currentUserId)
      ->whereNull('read_at')
      ->update(['read_at' => now()]);

    return view('messages.conversation_partial', compact('messages', 'otherUser'));
  }
}

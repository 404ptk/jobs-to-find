<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Application;
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

    $senderId = Auth::id();
    $receiverId = $request->input('receiver_id');
    $user = Auth::user();

    $conversationExists = Message::where(function ($q) use ($senderId, $receiverId) {
      $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
    })->orWhere(function ($q) use ($senderId, $receiverId) {
      $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
    })->exists();

    if (!$conversationExists) {
      if ($user->account_type === 'job_seeker') {
        return response()->json([
          'status' => 'error',
          'message' => 'Job seekers cannot initiate new conversations.'
        ], 403);
      }

      if ($user->account_type === 'employer') {
        $hasApplied = Application::where('user_id', $receiverId)
          ->whereHas('jobOffer', function ($query) use ($senderId) {
            $query->where('user_id', $senderId);
          })->exists();

        if (!$hasApplied) {
          return response()->json([
            'status' => 'error',
            'message' => 'Employers can only initiate conversations with candidates who have applied to their job offers.'
          ], 403);
        }
      }
    }

    $message = Message::create([
      'sender_id' => $senderId,
      'receiver_id' => $receiverId,
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

    $application = Application::where(function ($q) use ($currentUserId, $userId) {
      $q->where('user_id', $currentUserId)->whereHas('jobOffer', function ($q2) use ($userId) {
        $q2->where('user_id', $userId);
      });
    })->orWhere(function ($q) use ($currentUserId, $userId) {
      $q->where('user_id', $userId)->whereHas('jobOffer', function ($q2) use ($currentUserId) {
        $q2->where('user_id', $currentUserId);
      });
    })
      ->with('jobOffer')
      ->latest()
      ->first();

    $jobOffer = $application ? $application->jobOffer : null;

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

    return view('messages.conversation_partial', compact('messages', 'otherUser', 'jobOffer'));
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
    public function __construct()
    {
    $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return view('chat');
    }
// app/Http/Controllers/ChatsController.php

//remember to use


/**
 * Persist message to database
 *
 * @param  Request $request
 * @return Response
 */
public function sendMessage(Request $request)
{
  $user = Auth::user();

  $message = $user->messages()->create([
    'message' => $request->input('message')
  ]);

  broadcast(new MessageSent($user, $message))->toOthers();

  return ['status' => 'Message Sent!'];
}

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
    return Message::with('user')->get();
    }
}


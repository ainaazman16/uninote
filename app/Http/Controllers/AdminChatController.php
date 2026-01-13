<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;

class AdminChatController extends Controller
{
    public function index()
    {
        $chats = Chat::where('type', 'provider_admin')
            ->with('provider')
            ->latest()
            ->get();
        return view('admin.chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $messages = $chat->messages()->with('sender')->get();
        return view('admin.chats.show', compact('chat', 'messages'));
    }

    public function send(Request $request, Chat $chat)
    {
        $request->validate(['message' => 'required|string']);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        return back();
    }

    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()->with('sender')->latest('id')->get()->reverse()->values();
        return response()->json($messages);
    }
}


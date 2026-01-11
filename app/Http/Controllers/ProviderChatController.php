<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;

class ProviderChatController extends Controller
{
     public function index()
    {
        $provider = auth()->user();

        $chat = Chat::firstOrCreate([
            'provider_id' => $provider->id,
            'admin_id' => User::where('role', 'admin')->first()->id
        ]);

        $messages = $chat->messages()->with('sender')->get();

        return view('provider.chats.index', compact('chat', 'messages'));
    }
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        ChatMessage::create([
            'chat_id' => $request->chat_id,
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

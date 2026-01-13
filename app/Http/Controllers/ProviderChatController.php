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
            'admin_id' => User::where('role', 'admin')->first()->id,
            'type' => 'provider_admin',
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

    public function listChats()
    {
        $provider = auth()->user();

        // Get both admin support chat and student chats
        $chats = Chat::where('provider_id', $provider->id)
            ->with(['student', 'admin', 'messages'])
            ->latest('updated_at')
            ->get();

        return view('provider.chats.list', compact('chats'));
    }

    public function showStudentChat(Chat $chat)
    {
        abort_unless($chat->provider_id === auth()->id(), 403);

        $messages = $chat->messages()->with('sender')->get();

        return view('provider.chats.show', compact('chat', 'messages'));
    }

    public function sendToStudent(Request $request, Chat $chat)
    {
        abort_unless($chat->provider_id === auth()->id(), 403);

        $request->validate(['message' => 'required|string']);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back();
    }

    public function getStudentMessages(Chat $chat)
    {
        abort_unless($chat->provider_id === auth()->id(), 403);

        $messages = $chat->messages()->with('sender')->latest('id')->get()->reverse()->values();
        return response()->json($messages);
    }
}

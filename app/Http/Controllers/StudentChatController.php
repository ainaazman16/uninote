<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class StudentChatController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Admins cannot access chats.');
        $student = auth()->user();

        $chats = Chat::where('student_id', $student->id)
            ->where('type', 'student_provider')
            ->with(['provider', 'messages'])
            ->latest('updated_at')
            ->get();

        return view('student.chats.index', compact('chats'));
    }

    public function show(User $provider)
    {
        $student = auth()->user();

        // Ensure target is a provider
        abort_unless($provider->role === 'provider', 404);

        // Prevent chatting with yourself
        if ($provider->id === $student->id) {
            return redirect()->back()->with('error', 'You cannot chat with yourself.');
        }

        $adminId = User::where('role', 'admin')->value('id');
        abort_if(!$adminId, 500, 'Admin account required to initialize chat');

        $chat = Chat::firstOrCreate([
            'provider_id' => $provider->id,
            'student_id' => $student->id,
            'type' => 'student_provider',
            'admin_id' => $adminId,
        ]);

        $messages = $chat->messages()->with('sender')->get();

        return view('student.chats.show', compact('chat', 'provider', 'messages'));
    }

    public function send(Request $request, Chat $chat)
    {
        abort_unless($chat->student_id === auth()->id(), 403);

        $request->validate(['message' => 'required|string']);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back();
    }

    public function getMessages(Chat $chat)
    {
        abort_unless($chat->student_id === auth()->id(), 403);

        $messages = $chat->messages()->with('sender')->latest('id')->get()->reverse()->values();
        return response()->json($messages);
    }
}

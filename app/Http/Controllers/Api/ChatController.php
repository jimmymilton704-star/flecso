<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /*
    |-----------------------------------------
    | GET AUTH USER (ADMIN OR DRIVER)
    |-----------------------------------------
    */
    private function getAuthUser()
    {
        if (auth('driver')->check()) {
            return [
                'user' => auth('driver')->user(),
                'type' => 'driver'
            ];
        }

        return [
            'user' => auth()->user(),
            'type' => 'admin'
        ];
    }

    /*
    |-----------------------------------------
    | CREATE OR GET CHAT
    |-----------------------------------------
    */
    public function createOrGetChat(Request $request)
    {
        $request->validate(['admin_id' => 'required|exists:users,id', 'driver_id' => 'required|exists:drivers,id',]);
        $chat = Chat::where('admin_id', $request->admin_id)->where('driver_id', $request->driver_id)->first();
        if (!$chat) {
            $chat = Chat::create(['admin_id' => $request->admin_id, 'driver_id' => $request->driver_id,]);
        }
        return response()->json(['status' => true, 'data' => $chat]);
    }

    /*
    |-----------------------------------------
    | SEND MESSAGE
    |-----------------------------------------
    */
    public function sendMessage(Request $request)
    {
        $auth = $this->getAuthUser();

        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $chat = Chat::find($request->chat_id);

        //  SECURITY: Check user belongs to chat
        if (
            ($auth['type'] === 'admin' && $chat->admin_id != $auth['user']->id) ||
            ($auth['type'] === 'driver' && $chat->driver_id != $auth['user']->id)
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized chat access'
            ], 403);
        }

        $data = [
            'chat_id' => $request->chat_id,
            'sender_type' => $auth['type'],
            'sender_id' => $auth['user']->id,
            'message' => $request->message,
        ];

        /*
        |-----------------------------------------
        | FILE UPLOAD
        |-----------------------------------------
        */
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chat'), $name);

            $data['file'] = 'uploads/chat/' . $name;

            $mime = $file->getMimeType();

            if (str_contains($mime, 'image')) {
                $data['file_type'] = 'image';
            } elseif (str_contains($mime, 'video')) {
                $data['file_type'] = 'video';
            } else {
                $data['file_type'] = 'document';
            }
        }

        $message = Message::create($data);

        /*
        |-----------------------------------------
        | UPDATE CHAT LAST MESSAGE
        |-----------------------------------------
        */
        $chat->update([
            'last_message' => $request->message ?? 'File',
            'last_message_at' => now()
        ]);

        /*
        |-----------------------------------------
        | 🔥 WEBSOCKET EVENT
        |-----------------------------------------
        */
        event(new \App\Events\MessageSent($message));

        return response()->json([
            'status' => true,
            'data' => $message
        ]);
    }

    /*
    |-----------------------------------------
    | GET MESSAGES
    |-----------------------------------------
    */
    public function messages(Request $request)
    {
        $auth = $this->getAuthUser();

        $request->validate([
            'chat_id' => 'required|exists:chats,id'
        ]);

        $chat = Chat::find($request->chat_id);

        // 🔒 SECURITY CHECK
        if (
            ($auth['type'] === 'admin' && $chat->admin_id != $auth['user']->id) ||
            ($auth['type'] === 'driver' && $chat->driver_id != $auth['user']->id)
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $messages = Message::where('chat_id', $request->chat_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $messages
        ]);
    }

    /*
    |-----------------------------------------
    | MARK AS SEEN
    |-----------------------------------------
    */
    public function markAsSeen(Request $request)
    {
        $auth = $this->getAuthUser();

        $request->validate([
            'chat_id' => 'required|exists:chats,id',
        ]);

        Message::where('chat_id', $request->chat_id)
            ->where('sender_type', '!=', $auth['type'])
            ->update(['is_seen' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Messages marked as seen'
        ]);
    }

    /*
    |-----------------------------------------
    | CHAT LIST
    |-----------------------------------------
    */
    public function chatList()
    {
        $auth = $this->getAuthUser();

        if ($auth['type'] === 'admin') {
            $chats = Chat::with('driver')
                ->where('admin_id', $auth['user']->id)
                ->latest('last_message_at')
                ->get();
        } else {
            $chats = Chat::with('admin')
                ->where('driver_id', $auth['user']->id)
                ->latest('last_message_at')
                ->get();
        }

        return response()->json([
            'status' => true,
            'data' => $chats
        ]);
    }
}
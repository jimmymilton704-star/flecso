<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Driver;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /*
    |-----------------------------------------
    | GET AUTH USER
    |-----------------------------------------
    */
    private function getAuthUser()
    {
        return [
            'user' => auth()->user(),
            'type' => 'admin'
        ];
    }

    /*
    |-----------------------------------------
    | CHAT PAGE
    |-----------------------------------------
    */
    public function index()
    {
        $admin = auth()->user();

        $chats = Chat::with('driver')
            ->where('admin_id', $admin->id)
            ->latest('last_message_at')
            ->get();

        $drivers = Driver::latest()->get();

        return view('chat.index', compact(
            'chats',
            'drivers'
        ));
    }

    /*
    |-----------------------------------------
    | CREATE OR GET CHAT
    |-----------------------------------------
    */
    public function createOrGetChat(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $admin = auth()->user();

        $chat = Chat::where('admin_id', $admin->id)
            ->where('driver_id', $request->driver_id)
            ->first();

        if (!$chat) {

            $chat = Chat::create([
                'admin_id' => $admin->id,
                'driver_id' => $request->driver_id,
            ]);
        }

        $chat->load('driver');

        return response()->json([
            'status' => true,
            'data' => $chat
        ]);
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

        /*
        |-----------------------------------------
        | SECURITY CHECK
        |-----------------------------------------
        */
        if ($chat->admin_id != $auth['user']->id) {

            return response()->json([
                'status' => false,
                'message' => 'Unauthorized chat access'
            ], 403);
        }

        $data = [
            'chat_id' => $request->chat_id,
            'sender_type' => 'admin',
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
        | UPDATE CHAT
        |-----------------------------------------
        */
        $chat->update([
            'last_message' => $request->message ?? 'File',
            'last_message_at' => now()
        ]);

        /*
        |-----------------------------------------
        | LOAD RELATIONS
        |-----------------------------------------
        */
        $message->load('chat.driver');

        /*
        |-----------------------------------------
        | WEBSOCKET EVENT
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
    $request->validate([
        'chat_id' => 'required|exists:chats,id'
    ]);

    $admin = auth()->user();

    $chat = Chat::with([
        'driver',
        'driver.truck',
    ])->find($request->chat_id);

    /*
    |-----------------------------------------
    | SECURITY CHECK
    |-----------------------------------------
    */
    if ($chat->admin_id != $admin->id) {

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
        'chat' => $chat,
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
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
        ]);

        Message::where('chat_id', $request->chat_id)
            ->where('sender_type', 'driver')
            ->update([
                'is_seen' => true
            ]);

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
        $admin = auth()->user();

        $chats = Chat::with('driver')
            ->where('admin_id', $admin->id)
            ->latest('last_message_at')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $chats
        ]);
    }
}
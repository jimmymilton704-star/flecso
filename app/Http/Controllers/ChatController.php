<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Driver;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\VoiceTranslationService;

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


    // public function sendMessage(Request $request)
    // {
    //     $request->validate([
    //         'chat_id' => 'required|exists:chats,id',
    //         'message' => 'nullable|string',
    //         'file' => 'nullable|file|max:10240',
    //         'voice' => 'nullable|file|max:10240',
    //         'translate_to' => 'nullable|string',
    //     ]);

    //     $auth = $this->getAuthUser();

    //     $chat = Chat::findOrFail($request->chat_id);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | SECURITY
    //     |--------------------------------------------------------------------------
    //     */
    //     if ($chat->admin_id != $auth['user']->id) {

    //         return response()->json([
    //             'status' => false
    //         ]);
    //     }

    //     $filePath = null;
    //     $fileType = null;
    //     $fileName = null;

    //     /*
    //     |--------------------------------------------------------------------------
    //     | UPLOAD FILE
    //     |--------------------------------------------------------------------------
    //     */
    //     if ($request->hasFile('file')) {

    //         $file = $request->file('file');

    //         $filePath = $file->store('chat-files', 'public');

    //         $mime = $file->getMimeType();

    //         $fileType = str_contains($mime, 'image')
    //             ? 'image'
    //             : 'file';

    //         $fileName = $file->getClientOriginalName();
    //     }



    //     /*
    //     |--------------------------------------------------------------------------
    //     | CREATE MESSAGE
    //     |--------------------------------------------------------------------------
    //     */
    //     $message = Message::create([
    //         'chat_id' => $chat->id,
    //         'sender_type' => 'admin',
    //         'sender_id' => $auth['user']->id,
    //         'message' => $request->message,
    //         'file' => $filePath
    //             ? asset('storage/' . $filePath)
    //             : null,
    //         'file_type' => $fileType,
    //         'file_name' => $fileName,
    //     ]);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | UPDATE CHAT
    //     |--------------------------------------------------------------------------
    //     */
    //     $chat->update([
    //         'last_message' => $request->message
    //             ?: ($fileType === 'image'
    //                 ? '📷 Image'
    //                 : '📎 File'),
    //         'last_message_at' => now()
    //     ]);

    //     event(new \App\Events\MessageSent($message));

    //     return response()->json([
    //         'status' => true,
    //         'data' => $message
    //     ]);
    // }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id'      => 'required|exists:chats,id',
            'message'      => 'nullable|string',
            'file'         => 'nullable|file|max:10240',
            'voice'        => 'nullable|file|max:10240',
            'translate_to' => 'nullable|string',
        ]);

        $auth = $this->getAuthUser();

        $chat = Chat::findOrFail($request->chat_id);

        /*
    |--------------------------------------------------------------------------
    | SECURITY
    |--------------------------------------------------------------------------
    */
        if ($chat->admin_id != $auth['user']->id) {
            return response()->json([
                'status' => false
            ]);
        }

        $fileUrl = null;
        $fileType = null;
        $fileName = null;
        $messageText = $request->message;

        /*
    |--------------------------------------------------------------------------
    | OPENAI CLIENT
    |--------------------------------------------------------------------------
    */
        $openai = \OpenAI::client(env('OPENAI_API_KEY'));

        /*
    |--------------------------------------------------------------------------
    | NORMAL FILE UPLOAD TO PUBLIC
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $extension = strtolower($file->getClientOriginalExtension());

            if (!$extension) {
                $extension = 'file';
            }

            $fileName = 'chat-file-' . time() . '-' . uniqid() . '.' . $extension;

            $uploadFolder = public_path('uploads/chat-files');

            if (!file_exists($uploadFolder)) {
                mkdir($uploadFolder, 0777, true);
            }

            $file->move($uploadFolder, $fileName);

            $fileUrl = asset('uploads/chat-files/' . $fileName);

            $mime = $file->getMimeType();

            $fileType = str_contains($mime, 'image')
                ? 'image'
                : 'file';
        }

        /*
    |--------------------------------------------------------------------------
    | VOICE MESSAGE UPLOAD TO PUBLIC
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('voice')) {

            $voice = $request->file('voice');

            /*
        |--------------------------------------------------------------------------
        | GET SAFE EXTENSION
        |--------------------------------------------------------------------------
        */
            $extension = strtolower($voice->getClientOriginalExtension());

            if (!$extension) {
                $mime = $voice->getMimeType();

                $extension = match ($mime) {
                    'audio/webm', 'video/webm' => 'webm',
                    'audio/mpeg', 'audio/mp3' => 'mp3',
                    'audio/mp4', 'audio/m4a' => 'm4a',
                    'audio/wav', 'audio/x-wav' => 'wav',
                    'audio/ogg', 'application/ogg' => 'ogg',
                    default => 'webm',
                };
            }

            $voiceFileName = 'voice-' . time() . '-' . uniqid() . '.' . $extension;

            $voiceFolder = public_path('uploads/chat-voice');

            if (!file_exists($voiceFolder)) {
                mkdir($voiceFolder, 0777, true);
            }

            $voice->move($voiceFolder, $voiceFileName);

            $realAudioPath = public_path('uploads/chat-voice/' . $voiceFileName);

            $fileUrl = asset('uploads/chat-voice/' . $voiceFileName);
            $fileType = 'voice';
            $fileName = $voiceFileName;

            /*
        |--------------------------------------------------------------------------
        | SPEECH TO TEXT
        |--------------------------------------------------------------------------
        */
            $transcription = $openai->audio()->transcribe([
                'model' => 'whisper-1',
                'file'  => fopen($realAudioPath, 'r'),
            ]);

            $messageText = $transcription->text;

            /*
        |--------------------------------------------------------------------------
        | TRANSLATE IF REQUESTED
        |--------------------------------------------------------------------------
        */
            if ($request->translate_to) {

                $translate = $openai->chat()->create([
                    'model' => 'gpt-4.1-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Translate the message only. Do not add explanation.'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Translate this into {$request->translate_to}: {$messageText}"
                        ]
                    ],
                ]);

                $messageText = $translate->choices[0]->message->content;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | CREATE MESSAGE
    |--------------------------------------------------------------------------
    */
        $message = Message::create([
            'chat_id'     => $chat->id,
            'sender_type' => 'admin',
            'sender_id'   => $auth['user']->id,
            'message'     => $messageText,
            'file'        => $fileUrl,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
        ]);

        /*
    |--------------------------------------------------------------------------
    | LAST MESSAGE TEXT
    |--------------------------------------------------------------------------
    */
        $lastMessage = $messageText;

        if (!$lastMessage) {
            if ($fileType === 'image') {
                $lastMessage = '📷 Image';
            } elseif ($fileType === 'voice') {
                $lastMessage = '🎤 Voice Message';
            } elseif ($fileType === 'file') {
                $lastMessage = '📎 File';
            }
        }

        /*
    |--------------------------------------------------------------------------
    | UPDATE CHAT
    |--------------------------------------------------------------------------
    */
        $chat->update([
            'last_message'    => $lastMessage,
            'last_message_at' => now()
        ]);

        /*
    |--------------------------------------------------------------------------
    | BROADCAST
    |--------------------------------------------------------------------------
    */
        event(new \App\Events\MessageSent($message));

        return response()->json([
            'status' => true,
            'data'   => $message
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

    public function drivers()
    {
        $admin = auth()->user();

        $drivers = Driver::where('admin_id', $admin->id)
            ->orderBy('full_name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $drivers
        ]);
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'driver_ids' => 'required|array',
            'driver_ids.*' => 'exists:drivers,id',
            'message' => 'required|string'
        ]);

        $auth = $this->getAuthUser();

        $messages = [];

        foreach ($request->driver_ids as $driverId) {

            /*
            |--------------------------------------------------------------------------
            | GET OR CREATE CHAT
            |--------------------------------------------------------------------------
            */
            $chat = Chat::firstOrCreate([
                'admin_id' => $auth['user']->id,
                'driver_id' => $driverId
            ]);

            /*
            |--------------------------------------------------------------------------
            | CREATE MESSAGE
            |--------------------------------------------------------------------------
            */
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_type' => 'admin',
                'sender_id' => $auth['user']->id,
                'message' => $request->message,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ADD CHAT ID FOR FRONTEND SYNC (IMPORTANT)
            |--------------------------------------------------------------------------
            */
            $message->chat_id = $chat->id;

            $messages[] = $message;

            /*
            |--------------------------------------------------------------------------
            | UPDATE CHAT
            |--------------------------------------------------------------------------
            */
            $chat->update([
                'last_message' => $request->message,
                'last_message_at' => now()
            ]);

            /*
            |--------------------------------------------------------------------------
            | BROADCAST EVENT
            |--------------------------------------------------------------------------
            */
            event(new \App\Events\MessageSent($message));
        }

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }
}

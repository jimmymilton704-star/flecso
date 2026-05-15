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

    public function sendMessage(Request $request, VoiceTranslationService $voiceTranslationService)
    {
        $request->validate([
            'chat_id'      => 'required|exists:chats,id',
            'message'      => 'nullable|string',
            'file'         => 'nullable|file|max:20480',
            'voice'        => 'nullable|file|max:20480',
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
                'status'  => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        /*
    |--------------------------------------------------------------------------
    | REQUIRE MESSAGE OR FILE OR VOICE
    |--------------------------------------------------------------------------
    */
        if (
            !$request->message &&
            !$request->hasFile('file') &&
            !$request->hasFile('voice')
        ) {
            return response()->json([
                'status'  => false,
                'message' => 'Message, file, or voice is required'
            ], 422);
        }

        $fileUrl = null;
        $fileType = null;
        $fileName = null;
        $messageText = $request->message;

        /*
    |--------------------------------------------------------------------------
    | IMAGE / FILE UPLOAD DIRECTLY TO PUBLIC
    |--------------------------------------------------------------------------
    */
        /*
|--------------------------------------------------------------------------
| IMAGE / FILE UPLOAD DIRECTLY TO PUBLIC
|--------------------------------------------------------------------------
*/
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            if (!$file->isValid()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Uploaded file is not valid'
                ], 422);
            }

            /*
    |--------------------------------------------------------------------------
    | GET MIME BEFORE MOVE
    |--------------------------------------------------------------------------
    */
            $mime = $file->getMimeType();

            $extension = strtolower($file->getClientOriginalExtension());

            if (!$extension) {
                if (str_contains($mime, 'jpeg')) {
                    $extension = 'jpg';
                } elseif (str_contains($mime, 'png')) {
                    $extension = 'png';
                } elseif (str_contains($mime, 'webp')) {
                    $extension = 'webp';
                } elseif (str_contains($mime, 'gif')) {
                    $extension = 'gif';
                } else {
                    $extension = 'file';
                }
            }

            $fileName = 'chat-file-' . time() . '-' . uniqid() . '.' . $extension;

            $uploadFolder = public_path('uploads/chat-files');

            if (!file_exists($uploadFolder)) {
                mkdir($uploadFolder, 0777, true);
            }

            /*
    |--------------------------------------------------------------------------
    | MOVE FILE AFTER MIME CHECK
    |--------------------------------------------------------------------------
    */
            $file->move($uploadFolder, $fileName);

            $fileUrl = asset('uploads/chat-files/' . $fileName);

            if (str_contains($mime, 'image')) {
                $fileType = 'image';
            } elseif (str_contains($mime, 'video')) {
                $fileType = 'video';
            } else {
                $fileType = 'file';
            }

            $messageText = null;
        }
        /*
    |--------------------------------------------------------------------------
    | VOICE UPLOAD + TRANSLATED ITALIAN VOICE
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('voice')) {

            try {

                $voice = $request->file('voice');

                if (!$voice->isValid()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Uploaded voice is not valid'
                    ], 422);
                }

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

                $originalVoiceName = 'original-voice-' . time() . '-' . uniqid() . '.' . $extension;

                $voiceFolder = public_path('uploads/chat-voice/original');

                if (!file_exists($voiceFolder)) {
                    mkdir($voiceFolder, 0777, true);
                }

                $voice->move($voiceFolder, $originalVoiceName);

                $realAudioPath = public_path('uploads/chat-voice/original/' . $originalVoiceName);

                $targetLanguage = $request->translate_to ?: 'Italian';

                if ($targetLanguage === 'it') {
                    $targetLanguage = 'Italian';
                }

                $voiceResult = $voiceTranslationService->process(
                    $realAudioPath,
                    $targetLanguage
                );

                $messageText = $voiceResult['translated_text'];
                $fileUrl = $voiceResult['translated_voice'];
                $fileType = 'voice';

                $fileName = basename(
                    parse_url($voiceResult['translated_voice'], PHP_URL_PATH)
                );
            } catch (\Throwable $e) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Voice translation failed',
                    'error'   => $e->getMessage()
                ], 500);
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
            } elseif ($fileType === 'video') {
                $lastMessage = '🎥 Video';
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
    | BROADCAST EVENT
    |--------------------------------------------------------------------------
    */
        event(new \App\Events\MessageSent($message));

        return response()->json([
            'status'  => true,
            'message' => 'Message sent successfully',
            'data'    => [
                'id'          => $message->id,
                'chat_id'     => $message->chat_id,
                'sender_type' => $message->sender_type,
                'sender_id'   => $message->sender_id,
                'message'     => $message->message,
                'file'        => $message->file,
                'file_type'   => $message->file_type,
                'file_name'   => $message->file_name,
                'created_at'  => $message->created_at,
            ]
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

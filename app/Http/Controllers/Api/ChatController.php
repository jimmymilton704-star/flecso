<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\VoiceTranslationService;
use Illuminate\Support\Facades\Log;

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
    // public function sendMessage(Request $request)
    // {
    //     $auth = $this->getAuthUser();

    //     $request->validate([
    //         'chat_id' => 'required|exists:chats,id',
    //         'message' => 'nullable|string',
    //         'file' => 'nullable|file|max:10240',
    //     ]);

    //     $chat = Chat::find($request->chat_id);

    //     //  SECURITY: Check user belongs to chat
    //     if (
    //         ($auth['type'] === 'admin' && $chat->admin_id != $auth['user']->id) ||
    //         ($auth['type'] === 'driver' && $chat->driver_id != $auth['user']->id)
    //     ) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unauthorized chat access'
    //         ], 403);
    //     }

    //     $data = [
    //         'chat_id' => $request->chat_id,
    //         'sender_type' => $auth['type'],
    //         'sender_id' => $auth['user']->id,
    //         'message' => $request->message,
    //     ];

    //     /*
    //     |-----------------------------------------
    //     | FILE UPLOAD
    //     |-----------------------------------------
    //     */
    //     if ($request->hasFile('file')) {
    //         $file = $request->file('file');
    //         $name = time() . '_' . $file->getClientOriginalName();
    //         $file->move(public_path('uploads/chat'), $name);

    //         $data['file'] = 'uploads/chat/' . $name;

    //         $mime = $file->getMimeType();

    //         if (str_contains($mime, 'image')) {
    //             $data['file_type'] = 'image';
    //         } elseif (str_contains($mime, 'video')) {
    //             $data['file_type'] = 'video';
    //         } else {
    //             $data['file_type'] = 'document';
    //         }
    //     }

    //     $message = Message::create($data);

    //     /*
    //     |-----------------------------------------
    //     | UPDATE CHAT LAST MESSAGE
    //     |-----------------------------------------
    //     */
    //     $chat->update([
    //         'last_message' => $request->message ?? 'File',
    //         'last_message_at' => now()
    //     ]);

    //     /*
    //     |-----------------------------------------
    //     | 🔥 WEBSOCKET EVENT
    //     |-----------------------------------------
    //     */
    //     event(new \App\Events\MessageSent($message));

    //     return response()->json([
    //         'status' => true,
    //         'data' => $message
    //     ]);
    // }

    public function sendMessage(Request $request, VoiceTranslationService $voiceTranslationService)
    {
        $request->validate([
            'chat_id'      => 'required|exists:chats,id',
            'message'      => 'nullable|string',
            'file'         => 'nullable|file|max:10240',
            'voice'        => 'nullable|file|max:10240',
            'translate_to' => 'nullable|string',
        ]);

        $auth = $this->getAuthUser();

        if (!$auth['user']) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $chat = Chat::findOrFail($request->chat_id);

        /*
    |--------------------------------------------------------------------------
    | SECURITY
    |--------------------------------------------------------------------------
    */
        if (
            ($auth['type'] === 'admin' && $chat->admin_id != $auth['user']->id) ||
            ($auth['type'] === 'driver' && $chat->driver_id != $auth['user']->id)
        ) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized chat access'
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

            if (str_contains($mime, 'image')) {
                $fileType = 'image';
            } elseif (str_contains($mime, 'video')) {
                $fileType = 'video';
            } else {
                $fileType = 'file';
            }
        }

        /*
    |--------------------------------------------------------------------------
    | VOICE MESSAGE UPLOAD + TRANSLATE + ITALIAN VOICE
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('voice')) {

            try {

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

                /*
            |--------------------------------------------------------------------------
            | SAVE ORIGINAL VOICE IN PUBLIC
            |--------------------------------------------------------------------------
            */
                $originalVoiceName = 'original-voice-' . time() . '-' . uniqid() . '.' . $extension;

                $originalVoiceFolder = public_path('uploads/chat-voice/original');

                if (!file_exists($originalVoiceFolder)) {
                    mkdir($originalVoiceFolder, 0777, true);
                }

                $voice->move($originalVoiceFolder, $originalVoiceName);

                $originalAudioPath = public_path('uploads/chat-voice/original/' . $originalVoiceName);

                /*
            |--------------------------------------------------------------------------
            | TARGET LANGUAGE
            |--------------------------------------------------------------------------
            */
                $targetLanguage = $request->translate_to ?: 'Italian';

                if ($targetLanguage === 'it') {
                    $targetLanguage = 'Italian';
                }

                /*
            |--------------------------------------------------------------------------
            | CONVERT VOICE TO TEXT + TRANSLATE + CONVERT TO ITALIAN VOICE
            |--------------------------------------------------------------------------
            */
                $voiceResult = $voiceTranslationService->process(
                    $originalAudioPath,
                    $targetLanguage
                );

                /*
            |--------------------------------------------------------------------------
            | SAVE TRANSLATED TEXT AND TRANSLATED VOICE MP3
            |--------------------------------------------------------------------------
            */
                $messageText = $voiceResult['translated_text'];
                $fileUrl = $voiceResult['translated_voice'];
                $fileType = 'voice';

                $fileName = basename(
                    parse_url($voiceResult['translated_voice'], PHP_URL_PATH)
                );
            } catch (\Throwable $e) {

                Log::error('Voice translation failed', [
                    'error' => $e->getMessage()
                ]);

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
            'sender_type' => $auth['type'],
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

        /*
    |--------------------------------------------------------------------------
    | API RESPONSE
    |--------------------------------------------------------------------------
    */
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

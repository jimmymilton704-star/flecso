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
    | SECURITY CHECK
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

    
    if ($request->hasFile('file')) {

      try {
          $file = $request->file('file');

          if (!$file || !$file->isValid()) {
              return response()->json([
                  'status'  => false,
                  'message' => 'Invalid file upload',
                  'error'   => $file ? $file->getErrorMessage() : 'No file found',
              ], 422);
          }

          $extension = strtolower($file->getClientOriginalExtension());

          if (!$extension) {
              $extension = 'file';
          }

          $fileName = 'chat-file-' . time() . '-' . uniqid() . '.' . $extension;

          $uploadPath = public_path('uploads/chat-files');

          if (!is_dir($uploadPath)) {
              mkdir($uploadPath, 0775, true);
          }

          if (!is_writable($uploadPath)) {
              return response()->json([
                  'status'  => false,
                  'message' => 'Upload folder is not writable',
                  'path'    => $uploadPath,
              ], 500);
          }

          /*
          |--------------------------------------------------------------------------
          | Get mime type before moving file
          |--------------------------------------------------------------------------
          */
          $mime = $file->getMimeType() ?? '';

          if (str_contains($mime, 'image')) {
              $fileType = 'image';
          } elseif (str_contains($mime, 'video')) {
              $fileType = 'video';
          } else {
              $fileType = 'file';
          }

          /*
          |--------------------------------------------------------------------------
          | Now move file
          |--------------------------------------------------------------------------
          */
          $file->move($uploadPath, $fileName);

          $fileUrl = asset('uploads/chat-files/' . $fileName);

      } catch (\Throwable $e) {

          \Log::error('Chat file upload failed', [
              'error' => $e->getMessage(),
              'line'  => $e->getLine(),
              'file'  => $e->getFile(),
          ]);

          return response()->json([
              'status'  => false,
              'message' => 'File upload failed',
              'error'   => $e->getMessage(),
              'line'    => $e->getLine(),
          ], 500);
      }
  }

    /*
    |--------------------------------------------------------------------------
    | VOICE PROCESSING (UNCHANGED BUT SAFE)
    |--------------------------------------------------------------------------
    */
    if ($request->hasFile('voice')) {

        try {

            $voice = $request->file('voice');

            $extension = strtolower($voice->getClientOriginalExtension());

            if (!$extension) {
                $extension = 'webm';
            }

            $voiceFolder = public_path('uploads/chat-voice/original');

            if (!file_exists($voiceFolder)) {
                mkdir($voiceFolder, 0777, true);
            }

            $voiceName = 'voice-' . time() . '-' . uniqid() . '.' . $extension;

            $voice->move($voiceFolder, $voiceName);

            $audioPath = public_path('uploads/chat-voice/original/' . $voiceName);

            $targetLanguage = $request->translate_to ?: 'Italian';

            if ($targetLanguage === 'it') {
                $targetLanguage = 'Italian';
            }

            $voiceResult = $voiceTranslationService->process(
                $audioPath,
                $targetLanguage
            );

            $messageText = $voiceResult['translated_text'];
            $fileUrl = $voiceResult['translated_voice'];
            $fileType = 'voice';

            $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));

        } catch (\Throwable $e) {

            \Log::error('Voice processing failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Voice processing failed'
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
    | LAST MESSAGE
    |--------------------------------------------------------------------------
    */
    $lastMessage = $messageText;

    if (!$lastMessage) {
        $lastMessage = match ($fileType) {
            'image' => '📷 Image',
            'video' => '🎥 Video',
            'voice' => '🎤 Voice Message',
            default => '📎 File',
        };
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

    event(new \App\Events\MessageSent($message));

    return response()->json([
        'status'  => true,
        'message' => 'Message sent successfully',
        'data'    => $message
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

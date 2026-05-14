<?php

namespace App\Services;

use OpenAI;

class VoiceTranslationService
{
    public function process($audioPath, $targetLanguage = 'it')
    {
        $client = OpenAI::client(
            env('OPENAI_API_KEY')
        );

        /*
        |------------------------------------------------------------
        | SPEECH TO TEXT
        |------------------------------------------------------------
        */
        $transcription = $client->audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($audioPath, 'r'),
        ]);

        $originalText = $transcription->text;

        /*
        |------------------------------------------------------------
        | TRANSLATE
        |------------------------------------------------------------
        */
        $translation = $client->chat()->create([
            'model' => 'gpt-4.1-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' =>
                        "Translate this to {$targetLanguage}: {$originalText}"
                ]
            ]
        ]);

        $translatedText =
            $translation->choices[0]->message->content;

        /*
        |------------------------------------------------------------
        | TEXT TO SPEECH
        |------------------------------------------------------------
        */
        $speech = $client->audio()->speech([
            'model' => 'tts-1',
            'voice' => 'alloy',
            'input' => $translatedText,
        ]);

        $translatedVoice =
            'uploads/chat-voice/' . time() . '.mp3';

        file_put_contents(
            public_path($translatedVoice),
            $speech
        );

        return [
            'original_text' => $originalText,
            'translated_text' => $translatedText,
            'translated_voice' => asset($translatedVoice),
        ];
    }
}
<?php

namespace App\Services;

use OpenAI;

class VoiceTranslationService
{
    public function process($audioPath, $targetLanguage = 'Italian')
    {
        $client = OpenAI::client(
            env('OPENAI_API_KEY')
        );

        /*
        |------------------------------------------------------------
        | CHECK AUDIO FILE
        |------------------------------------------------------------
        */
        if (!file_exists($audioPath)) {
            throw new \Exception('Audio file not found.');
        }

        /*
        |------------------------------------------------------------
        | SPEECH TO TEXT
        |------------------------------------------------------------
        */
        $transcription = $client->audio()->transcribe([
            'model' => 'whisper-1',
            'file'  => fopen($audioPath, 'r'),
        ]);

        $originalText = trim($transcription->text ?? '');

        if (!$originalText) {
            throw new \Exception('No speech detected in audio.');
        }

        /*
        |------------------------------------------------------------
        | TRANSLATE TEXT TO ITALIAN
        |------------------------------------------------------------
        */
        $translation = $client->chat()->create([
            'model' => 'gpt-4.1-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional translator. Translate the user text into natural Italian only. Do not explain. Do not add quotation marks. Do not add English.'
                ],
                [
                    'role' => 'user',
                    'content' => $originalText
                ]
            ]
        ]);

        $translatedText = trim(
            $translation->choices[0]->message->content ?? ''
        );

        if (!$translatedText) {
            throw new \Exception('Translation failed.');
        }

        /*
        |------------------------------------------------------------
        | CREATE PUBLIC FOLDER
        |------------------------------------------------------------
        */
        $folder = public_path('uploads/chat-voice');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        /*
        |------------------------------------------------------------
        | TEXT TO ITALIAN SPEECH
        |------------------------------------------------------------
        */
        $speech = $client->audio()->speech([
            'model' => 'gpt-4o-mini-tts',
            'voice' => 'alloy',
            'input' => $translatedText,
            'instructions' => 'Speak clearly in natural Italian with an Italian pronunciation and friendly conversational tone.',
            'response_format' => 'mp3',
        ]);

        /*
        |------------------------------------------------------------
        | SAVE ITALIAN VOICE
        |------------------------------------------------------------
        */
        $fileName = 'italian-voice-' . time() . '-' . uniqid() . '.mp3';

        $translatedVoicePath = 'uploads/chat-voice/' . $fileName;

        file_put_contents(
            public_path($translatedVoicePath),
            $speech
        );

        return [
            'original_text'     => $originalText,
            'translated_text'   => $translatedText,
            'translated_voice'  => asset($translatedVoicePath),
        ];
    }
}
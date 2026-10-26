<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translate a single piece of text from source to target locale.
     */
    public function translate(string $text, string $targetLocale, string $sourceLocale = 'en'): string
    {
        $text = trim($text);
        if ($text === '' || $targetLocale === $sourceLocale) {
            return $text;
        }

        // Split by paragraphs to avoid length limitations and keep structure
        $paragraphs = preg_split('/(\r\n\r\n|\n\n)/', $text);
        $translatedParagraphs = [];

        foreach ($paragraphs as $para) {
            $para = trim($para);
            if ($para === '') {
                $translatedParagraphs[] = '';
                continue;
            }

            // If a paragraph is longer than 450 characters, break it by sentences
            if (mb_strlen($para) > 450) {
                $sentences = preg_split('/(?<=[.?!])\s+/', $para);
                $translatedSentences = [];
                foreach ($sentences as $sentence) {
                    $sentence = trim($sentence);
                    if ($sentence !== '') {
                        $translatedSentences[] = $this->translateChunk($sentence, $targetLocale, $sourceLocale);
                    }
                }
                $translatedParagraphs[] = implode(' ', $translatedSentences);
            } else {
                $translatedParagraphs[] = $this->translateChunk($para, $targetLocale, $sourceLocale);
            }
        }

        return implode("\n\n", $translatedParagraphs);
    }

    /**
     * Translates a chunk using MyMemory API with error protection.
     */
    protected function translateChunk(string $chunk, string $targetLocale, string $sourceLocale = 'en'): string
    {
        try {
            $response = Http::timeout(6)
                ->withHeaders([
                    'User-Agent' => 'DearlyPostalSanctuary/1.0 (Laravel; Education/Non-Commercial)',
                ])
                ->get('https://api.mymemory.translated.net/get', [
                    'q' => $chunk,
                    'langpair' => "{$sourceLocale}|{$targetLocale}",
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['responseData']['translatedText'])) {
                    $translated = html_entity_decode($data['responseData']['translatedText'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    // Avoid returning warning messages from API if any
                    if (!str_contains($translated, 'MYMEMORY WARNING')) {
                        return $translated;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Translation API exception for [{$sourceLocale}->{$targetLocale}]: " . $e->getMessage());
        }

        // Fallback to original chunk if translation service is unavailable
        return $chunk;
    }

    /**
     * Translate an associative array of fields to multiple target locales.
     *
     * @param array<string, string> $fields ['field_key' => 'English Text']
     * @param array<string> $targetLocales ['bn', 'hi', 'ar', 'es', 'fr', 'pt']
     * @return array<string, array<string, string>> ['bn' => ['field_key' => 'বাংলা টেক্সট']]
     */
    public function translateBatch(array $fields, array $targetLocales, string $sourceLocale = 'en'): array
    {
        $results = [];

        foreach ($targetLocales as $locale) {
            if ($locale === $sourceLocale) {
                continue;
            }

            $results[$locale] = [];
            foreach ($fields as $key => $text) {
                if (is_string($text) && trim($text) !== '') {
                    $results[$locale][$key] = $this->translate($text, $locale, $sourceLocale);
                } else {
                    $results[$locale][$key] = $text;
                }
            }
        }

        return $results;
    }
}

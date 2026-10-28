<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_key',
        'option_text',
        'translations',
        'score_value',
        'order_index',
    ];

    protected $casts = [
        'translations' => 'array',
        'score_value' => 'integer',
        'order_index' => 'integer',
    ];

    public function getOptionTextAttribute(?string $value): string
    {
        $locale = app()->getLocale();
        if ($locale !== 'en' && !empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        return $value ?? '';
    }

    public function getLocalizedOptionText(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale !== 'en' && !empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        return $this->attributes['option_text'] ?? '';
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

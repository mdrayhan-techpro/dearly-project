<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'dimension',
        'question_text',
        'translations',
        'weight',
        'is_active',
        'order_index',
    ];

    protected $casts = [
        'translations' => 'array',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function getQuestionTextAttribute(?string $value): string
    {
        $locale = app()->getLocale();
        if ($locale !== 'en' && !empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        return $value ?? '';
    }

    public function getLocalizedQuestionText(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale !== 'en' && !empty($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        return $this->attributes['question_text'] ?? '';
    }

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order_index');
    }
}

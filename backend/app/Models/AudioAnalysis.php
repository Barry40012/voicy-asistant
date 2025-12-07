<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'audio_id',
        'transcript',
        'summary',
        'actions',
        'generated_reply',
        'confidence_scores',
        'ia_provider',
    ];

    protected $casts = [
        'actions' => 'array',
        'confidence_scores' => 'array',
    ];

    /**
     * Get the audio for this analysis
     */
    public function audio(): BelongsTo
    {
        return $this->belongsTo(Audio::class);
    }
}

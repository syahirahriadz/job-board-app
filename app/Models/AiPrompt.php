<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\CommonMark\CommonMarkConverter;

class AiPrompt extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',
        'prompt',
        'response',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'prompt' => 'string',
            'response' => 'string',
            'error_message' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedResponse(): string
    {
        if (!$this->response) {
            return '';
        }

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $converter->convert($this->response)->getContent();
    }
}

<?php

namespace App\Jobs;

use App\Models\AiPrompt;
use App\Services\DeepseekService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendAiPrompt implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $requestId,
        protected string $prompt,
        protected int $userId,
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(DeepseekService $deepseekService): void
    {
        $aiPrompt = AiPrompt::where('request_id', $this->requestId)->first();
        $response = $deepseekService->generate($this->prompt);

        try {
            $aiPrompt->update([
                'response' => $response,
                'status' => 'Completed',
            ]);
        } catch (\Exception $e) {
            $aiPrompt->update([
                'status' => 'Failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error('Error processing AI prompt.', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage(),
            ]);
        }

    }
}

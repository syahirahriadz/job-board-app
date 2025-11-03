<?php

namespace App\Livewire;

use App\Jobs\SendAiPrompt;
use App\Models\AiPrompt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use Livewire\Attributes\On;
use Livewire\Component;

class AiDescriptionModal extends Component
{
    public bool $showModal = false;
    public string $prompt = 'Generate a professional job description for a software developer position in Malaysia.';
    public ?AiPrompt $currentPrompt = null;
    public string $requestId = '';

    #[On('openAiModal')]
    public function openModal(): void
    {
        $this->showModal = true;
        $this->reset(['currentPrompt', 'requestId']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['prompt', 'currentPrompt', 'requestId']);
        $this->prompt = 'Generate a professional job description for a software developer position in Malaysia.';
    }

    public function generatePrompt(): void
    {
        $this->validate([
            'prompt' => 'required|string|min:10|max:1000',
        ]);

        $this->requestId = Str::uuid()->toString();

        $this->currentPrompt = AiPrompt::create([
            'user_id' => Auth::id(),
            'request_id' => $this->requestId,
            'prompt' => $this->prompt,
            'status' => 'pending',
        ]);

        // Dispatch the job to generate AI response
        SendAiPrompt::dispatch(
            $this->requestId,
            $this->prompt,
            Auth::id()
        );
    }

    public function checkStatus(): void
    {
        if ($this->currentPrompt) {
            $this->currentPrompt->refresh();
        }
    }

    public function getResponseHtmlProperty(): string
    {
        if (!$this->currentPrompt || !$this->currentPrompt->response) {
            return '';
        }

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $converter->convert($this->currentPrompt->response)->getContent();
    }

    public function useResponse(): void
    {
        if ($this->currentPrompt && $this->currentPrompt->response) {
            // Convert markdown to HTML
            // $converter = new CommonMarkConverter([
            //     'html_input' => 'strip',
            //     'allow_unsafe_links' => false,
            // ]);

            $converter = new CommonMarkConverter();

            $htmlResponse = $converter->convert($this->currentPrompt->response)->getContent();

            // Dispatch event to parent component with the converted HTML response
            $this->dispatch('ai-response-generated', $htmlResponse);
            $this->closeModal();
        }
    }

    public function render()
    {
        return view('livewire.ai-description-modal');
    }
}

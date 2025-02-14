<?php

namespace App\Jobs;

use App\Models\AiRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GenerateImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contentId;
    protected $content;
    protected $size;
    protected $lang;

    public function __construct($contentId, $content, $size, $lang)
    {
        $this->contentId = $contentId;
        $this->content = $content;
        $this->size = $size;
        $this->lang = $lang;
    }

    public function handle()
    {
        // Prepare the data for the external API
        $data = [
            "postId" => $this->contentId,  // Use contentId as postId
            "postData" => [
                "content" => $this->content,
                "priority" => 100000,
                "content_id" => $this->contentId,
                "size" => $this->size,
            ],
            "lang" => $this->lang  // Dynamic lang value
        ];

        // Send data to external API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://sse.almkal.com/posts/add', $data);

        // Check the response and update the status in the database
        $aiRequest = AiRequest::where('content_id', $this->contentId)->first();
        if ($aiRequest) {
            $aiRequest->status = $response->successful() ? 'completed' : 'failed';
            $aiRequest->save();
        }
    }
}

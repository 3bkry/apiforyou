<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateImageJob;
use App\Models\AiRequest;

class AiGenerateController extends Controller
{
    public function generateImage(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'content' => 'required|string',
            'size' => 'required|string',
            'content_id' => 'required|string',
            'lang' => 'nullable|string', // Make lang optional
        ]);

        // If lang is not provided, default to 'gpt'
        $lang = $request->input('lang', 'gpt');  // Default to 'gpt' if not provided

        // Save the request to the database with "pending" status
        $aiRequest = AiRequest::create([
            'content_id' => $request->input('content_id'),
            'content' => $request->input('content'),
            'status' => 'pending', // Status is pending initially
        ]);

        // Dispatch the job to the queue, passing lang along with content_id, content, and size
        GenerateImageJob::dispatch($request->input('content_id'), $request->input('content'), $request->input('size'), $lang);

        // Return immediate response to the client with content_id
        return response()->json([
            'message' => 'Image generation request is queued.',
            'content_id' => $request->input('content_id'),
        ], 202);
    }

    public function checkStatus($content_id)
    {
        // Find the request by content_id
        $aiRequest = AiRequest::where('content_id', $content_id)->first();

        if (!$aiRequest) {
            return response()->json([
                'message' => 'Content ID not found.',
            ], 404);
        }

        return response()->json([
            'content_id' => $content_id,
            'status' => $aiRequest->status, // Return the status of the request
            'image_url' => $aiRequest->image_url, // Return the image URL if available
        ]);
    }

    public function imageGenerationDone(Request $request, $content_id)
    {
        // Validate incoming data (image URL)
        $request->validate([
            'image_url' => 'required|url',
        ]);

        // Find the request by content_id
        $aiRequest = AiRequest::where('content_id', $content_id)->first();

        if (!$aiRequest) {
            return response()->json([
                'message' => 'Content ID not found.',
            ], 404);
        }

        // Update the request with the image URL and set the status to "completed"
        $aiRequest->image_url = $request->input('image_url');
        $aiRequest->status = 'completed'; // Mark as completed
        $aiRequest->save();

        // Return a success response
        return response()->json([
            'message' => 'Image generation completed and URL saved.',
            'content_id' => $content_id,
            'image_url' => $request->input('image_url'),
        ]);
    }
}

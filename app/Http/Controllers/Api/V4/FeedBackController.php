<?php

namespace App\Http\Controllers\Api\V4;

use App\Http\Controllers\Controller;
use App\Mail\FeedbackReceived;
use App\Models\Feedback;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedBackController extends Controller
{
    use ApiResponses;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required|uuid',
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:bug,suggestion,question',
            'message' => 'required|string|max:1000',
            'question_id' => 'nullable|integer',
            'status' => 'nullable|in:pending,reviewed,resolved',
        ]);

        $feedback = Feedback::updateOrCreate(
            ['uuid' => $validated['uuid']],
            [
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'message' => $validated['message'],
                'question_id' => $validated['question_id'] ?? null,
                'status' => $validated['status'] ?? 'pending',
            ]
        );

        if ($feedback->wasRecentlyCreated) {
            try {
                Mail::to('support@cfls.be')->send(new FeedbackReceived($feedback));
            } catch (\Throwable $e) {
                Log::error('feedback.mail.failed', [
                    'feedback_id' => $feedback->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->ok('Feedback submitted successfully', [
            'id' => $feedback->id,
            'uuid' => $feedback->uuid,
        ]);
    }
}

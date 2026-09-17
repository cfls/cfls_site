<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackReceived;

class FeedBackController extends Controller
{
    use ApiResponses;
    public function index()
    {
        $feedback = Feedback::with('user')
            ->latest()
            ->paginate(20);

      //  return view('admin.feedback.index', compact('feedback'));
    }

    public function store(Request $request, User $user) {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:bug,suggestion,question',
            'message' => 'required|string|max:1000',
            'question_id' => 'nullable|integer',
            'status' => 'nullable|in:pending,reviewed,resolved',
        ]);

        $feedback = Feedback::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'question_id' => $validated['question_id'] ?? null,
            'status' => $validated['status'] ?? 'pending',
        ]);

        Mail::to('support@cfls.be')->send(new FeedbackReceived($feedback));

        return $this->ok('Feedback submitted successfully', ['id' => $feedback->id]);
    }

    public function show(Feedback $feedback)
    {
        $feedback->load('user');
       // return view('admin.feedback.show', compact('feedback'));
    }

    public function markAsReviewed(Feedback $feedback)
    {
        $feedback->markAsReviewed();
        return back()->with('success', 'Feedback marked as reviewed');
    }

    public function markAsResolved(Feedback $feedback)
    {
        $feedback->markAsResolved();
        return back()->with('success', 'Feedback marked as resolved');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Feedback deleted');
    }
}
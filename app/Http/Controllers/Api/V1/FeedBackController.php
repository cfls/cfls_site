<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;

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
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:bug,suggestion,question',
            'message' => 'required|string|max:1000',
            'question_id' => 'nullable|integer',
        ]);

        Feedback::create([
            'user_id' => $request->input('user_id'),
            'type' => $request->input('type'),
            'message' => $request->input('message'),
            'question_id' => $request->input('question_id'),
        ]);

        return $this->ok('Feedback submitted successfully');
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
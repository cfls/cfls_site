<?php

namespace App\Http\Controllers;

use App\Models\AccountDeletionFeedback;
use Illuminate\Http\Request;

class AccountDeletionSurveyController extends Controller
{
    public function show(Request $request, string $name, string $email)
    {
        return view('deletion-survey.show', [
            'name'  => $name,
            'email' => $email,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name'  => ['nullable', 'string', 'max:255'],
            'user_email' => ['nullable', 'email', 'max:255'],
            'reason'     => ['required', 'string', 'in:not_useful,too_complicated,found_alternative,too_expensive,technical_issues,other'],
            'comment'    => ['nullable', 'string', 'max:1000'],
        ]);

        AccountDeletionFeedback::create($validated);

        return view('deletion-survey.thanks');
    }
}
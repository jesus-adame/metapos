<?php

namespace App\Http\Controllers\Auth;

use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('home', absolute: false))
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}

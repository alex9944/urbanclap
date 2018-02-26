<?php

namespace App\Http\Controllers;

use App\Traits\ActivationTrait;
use App\Models\Activation;

class ActivateController extends Controller
{

    use ActivationTrait;

    public function activate($token)
    {
        if (auth()->user()->activated) {

            return redirect()->route('public.home')
                ->with('status', 'success')
                ->with('message', 'Your email is already activated.');
        }

        $activation = Activation::where('token', $token)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (empty($activation)) {

            return redirect()->route('public.home')
                ->with('status', 'wrong')
                ->with('message', 'No such token in the database!');

        }

        auth()->user()->activated = true;
        auth()->user()->save();

        $activation->delete();

        session()->forget('above-navbar-message');

        return redirect()->route('public.home')
            ->with('status', 'success')
            ->with('message', 'You successfully activated your email!');

    }

    public function resend()
    {
        if (auth()->user()->activated == false) {
            $this->initiateEmailActivation(auth()->user());

            return redirect()->route('public.home')
                ->with('status', 'success')
                ->with('message', 'Activation email sent.');
        }

        return redirect()->route('public.home')
            ->with('status', 'success')
            ->with('message', 'Already activated.');
    }
}
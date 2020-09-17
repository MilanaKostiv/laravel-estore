<?php

namespace App\Http\Controllers;

/**
 * Confirmation page controller.
 */
class ConfirmationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|
     * \Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        if (! session()->has('success_message')) {
            return redirect('/');
        }
        return view('thankyou');
    }
}


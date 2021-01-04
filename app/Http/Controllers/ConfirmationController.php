<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;

/**
 * Confirmation page controller.
 */
class ConfirmationController extends Controller
{
    /**
     * Renders page after successful order.
     *
     * @return Factory|RedirectResponse|
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


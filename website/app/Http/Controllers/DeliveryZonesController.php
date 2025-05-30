<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryZonesController extends Controller
{
    /**
     * Wyświetla stronę ze strefami dostaw.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('delivery-zones');
    }
}
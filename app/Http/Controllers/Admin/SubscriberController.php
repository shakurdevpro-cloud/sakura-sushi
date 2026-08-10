<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportSubscribersCsvJob;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(30);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function export()
    {
        ExportSubscribersCsvJob::dispatch();

        return back()->with('status', "Export en cours de génération, il sera disponible dans storage/app/exports.");
    }
}
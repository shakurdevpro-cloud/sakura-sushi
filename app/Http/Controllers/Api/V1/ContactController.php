<?php
// app/Http/Controllers/Api/V1/ContactController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        $contactMessage = ContactMessage::create($data);

        Mail::to(config('mail.admin_address', 'hello@sakurasushi.com'))
            ->queue(new ContactMessageMail($contactMessage));

        return response()->json(['message' => 'Votre message a bien été envoyé.'], 201);
    }
}

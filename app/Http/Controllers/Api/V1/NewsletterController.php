<?php
// app/Http/Controllers/Api/V1/NewsletterController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\NewsletterService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(protected NewsletterService $newsletterService) {}

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:180'],
            'name' => ['nullable', 'string', 'max:100'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        $this->newsletterService->subscribe($data['email'], $data['name'] ?? null, $data['source'] ?? null);

        return response()->json(['message' => 'Merci ! Vérifiez votre email pour confirmer votre inscription.'], 201);
    }

    public function confirm(string $token)
    {
        $this->newsletterService->confirm($token);

        return response()->json(['message' => 'Abonnement confirmé avec succès.']);
    }

    public function unsubscribe(string $token)
    {
        $this->newsletterService->unsubscribe($token);

        return response()->json(['message' => 'Vous avez été désabonné avec succès.']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\LoyaltyAccount;
use App\Models\User;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
        ]);

        LoyaltyAccount::create(['user_id' => $user->id]);

        $user->notify(new WelcomeNotification);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides.'], 401);
        }

        if (! $user->is_active) {
            return response()->json(['message' => 'Compte désactivé.'], 403);
        }

        $abilities = $user->getRoleNames()->isNotEmpty()
            ? $user->getRoleNames()->map(fn($role) => "role:{$role}")->toArray()
            : ['role:customer'];

        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('roles'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name'   => ['sometimes', 'string', 'min:2', 'max:100'],
            'phone'  => ['sometimes', 'nullable', 'string', 'max:20'],
            'avatar' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json($user);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        Password::sendResetLink($request->only('email'));

        return response()->json(['message' => 'Email de réinitialisation envoyé si le compte existe.']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Handle login and return a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified. Please check your inbox for the verification link.'], 403);
        }

        // Create a new token for the user
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Handle email verification.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail($token)
    {
        \Log::info('Verification attempt for token: ' . $token);

        // Try to find the user by the verification token
        $user = User::where('verification_token', $token)->first();

        // If no user is found by token, check for already verified emails
        if (!$user) {
            \Log::info('No user found by token, checking for already verified emails.');

            $user = User::whereNotNull('email_verified_at')
                        ->whereNull('verification_token')
                        ->first();

            if ($user) {
                \Log::info('Email already verified for user ID: ' . $user->id);
                return view('verify-already', compact('user')); // Show "Email Already Verified" page
            }

            \Log::error('User not found or invalid token.');
            return response()->json(['message' => 'Verification failed. User or token is invalid.'], 404);
        }

        // Check if the user's email is already verified
        if ($user->hasVerifiedEmail()) {
            \Log::info('Email already verified for user ID: ' . $user->id);
            return view('verify-already', compact('user')); // Show "Email Already Verified" page
        }

        // Mark the email as verified
        $user->email_verified_at = now();
        $user->verification_token = null; // Clear the token after verification
        $user->save();

        \Log::info('Email verified successfully for user ID: ' . $user->id);

        return view('verify-success', compact('user')); // Show success page for newly verified email
    }

    /**
     * Handle logout and revoke the token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the user's current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

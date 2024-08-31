<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Get the currently authenticated user
        $user = $request->user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Update the password (model's mutator will handle hashing)
        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Get the user's profile picture URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfilePicture(Request $request)
    {
        $user = $request->user();

        // Default to 'default_profile_picture.png' if no profile picture is set
        $profilePicturePath = $user->profile_picture ?? 'profile_pictures/default_profile_picture.png';

        // Generate the URL to the profile picture
        $profilePictureUrl = Storage::disk('public')->url($profilePicturePath);

        return response()->json([
            'profile_picture_url' => $profilePictureUrl,
        ]);
    }

    /**
     * Update the user's profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfilePicture(Request $request)
    {
        // Log all request data for debugging
        Log::info('Request Data:', $request->all());

        // Validate the profile picture
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Handle the profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filePath = $file->store('profile_pictures', 'public');

            // Log the storage path for debugging
            Log::info('File stored at path:', ['filePath' => $filePath]);

            // If the user already has a profile picture, delete the old one
            if ($user->profile_picture && $user->profile_picture != 'default_profile_picture.png') {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Update the user record with the new profile picture path
            $user->profile_picture = $filePath;
            $user->save();

            return response()->json([
                'message' => 'Profile picture updated successfully',
                'user' => $user,
                'profile_picture_url' => url('storage/' . $filePath),
            ]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}

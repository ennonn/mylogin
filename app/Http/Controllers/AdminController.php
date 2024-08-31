<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; 
use Spatie\Permission\Models\Role;
use App\Mail\VerificationEmail;

class AdminController extends Controller
{

    public function createUser(Request $request)
    {
       
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', 
            'role' => 'required|string|in:user,admin',
        ]);
    
      
        $existingUser = User::where('email', $validatedData['email'])->first();
        if ($existingUser) {
           
            return response()->json([
                'message' => 'The email has already been taken.',
            ], 409); 
        }
          
        $plainPassword = Str::random(6); 
           
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => $plainPassword,
            'role' => $validatedData['role'],
            'email_verified_at' => null,
            'verification_token' => Str::random(60),
        ]);
    
        $user->assignRole($validatedData['role']);
    
        try {
            Mail::to($user->email)->send(new VerificationEmail($user, $plainPassword));
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error sending verification email: ' . $e->getMessage());
    
            return response()->json(['message' => 'User created, but failed to send verification email.'], 500);
        }
    
        \Log::info('User created and verification email sent: ' . $user->email);
    
        return response()->json([
            'message' => 'User created successfully. Verification email sent.',
            'user' => $user,
        ], 201);
    }
    

public function updateUser(Request $request, User $user)
{

    $validatedData = $request->validate([
        'first_name' => 'sometimes|required|string|max:255',
        'last_name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|required|string|min:8',
        'role' => 'sometimes|required|string|exists:roles,name',
    ]);

    // Debugging: Log the validated data to ensure it's being received correctly
    \Log::info('Validated Data:', $validatedData);

    // Update the user's information (password will be hashed automatically)
    $user->update([
        'first_name' => $validatedData['first_name'] ?? $user->first_name,
        'last_name' => $validatedData['last_name'] ?? $user->last_name,
        'email' => $validatedData['email'] ?? $user->email,
        'password' => $validatedData['password'] ?? $user->password, // Assign the password directly
    ]);

    // Debugging: Log the user data after the update to check if it was successful
    \Log::info('User after update:', $user->toArray());

    // Update the role if provided
    if (isset($validatedData['role'])) {
        $user->syncRoles([$validatedData['role']]);
        \Log::info('User role after update:', $user->getRoleNames()->toArray());
    }

    // Return a response
    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ], 200);
}


    public function deleteUser(User $user)
        {
            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Delete the user
            $user->delete();

            // Return a response
            return response()->json(['message' => 'User deleted successfully'], 200);
        }


}

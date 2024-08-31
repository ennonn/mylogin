<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .heading {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .message, .note {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .password-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .password-label {
            margin top: 10px;
            margin-right: 5px;
            font-weight: bold;
            color: #666;
        }
        .code {
            font-size: 16px;
            background-color: #e0f7fa;
            color: #333;
            padding: 7px 12px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            margin-left: 5px;
        }
        .button-container {
            text-align: center;
            margin-top: 10px; /* Slightly increased margin for better spacing */
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="heading">Hello, {{ $user->first_name }} {{ $user->last_name }}!</h1>
        
        <p class="message">Welcome to Barangay Cabcabon Sangguniang Kabataan RMS!</p>

        <p class="message">Here are your account details:</p>

        <p class="message"><strong>Email:</strong> {{ $user->email }}</p>
        
        <!-- Align the password label and box -->
        <div class="password-container">
            <span class="password-label">Password:</span>
            <div class="code"><b>{{ $password }}</b></div>
        </div>

        <p class="note">Please change your password immediately after logging in for security purposes.</p>

        <p class="message">To complete your registration, you need to verify your email address. Please click the button below to verify:</p>
        
        <!-- Centering the button -->
        <div class="button-container">
            <a href="{{ $verificationUrl }}" target="_blank" class="button">Verify Your Email</a>
        </div>
        
        <p class="message">If you did not request this account, please ignore this email.</p>
        
        <p>Best regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>

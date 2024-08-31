<!-- resources/views/verify-already.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Already Verified</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .svg-icon {
            width: 80px; /* Adjust size to match */
            height: 80px; /* Adjust size to match */
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #d63384;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #c02374;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Embed the SVG icon here -->
        <img src="{{ asset('storage/images/information-circle.svg') }}" alt="Information Icon" class="svg-icon">
        <div class="title">Email Already Verified</div>
        <div class="message">Your email address <b>{{ $user->email }}</b> is already verified.</div>
        <a href="{{ url('/login') }}" class="button">Sign in to Patient Access</a>
    </div>
</body>
</html>

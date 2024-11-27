<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #4CAF50;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }

        .content {
            margin: 20px 0;
            line-height: 1.6;
            color: #333333;
        }

        .content p {
            margin: 10px 0;
        }

        .verify-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            color: #888888;
            font-size: 12px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Welcome to Samahang Nayon Hotel</h1>
        </div>
        <div class="content">
            <p>Dear {{ $guest->FirstName }},</p>
            <p>Welcome! Your account has been successfully created at Samahang Nayon Hotel.</p>
            <p>Here are your account details:</p>
            <p><strong>Email:</strong> {{ $guest->EmailAddress }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p>For security purposes, please change your password after logging in for the first time.</p>
            <p>If you have any questions or need assistance, feel free to contact us.</p>
            <p>We look forward to making your stay memorable!</p>
        </div>
        <div class="footer">
            <p>Samahang Nayon Hotel</p>
            <p><a href="https://www.nasaph8210.online/download-app">Visit our website</a></p>
        </div>
    </div>
</body>

</html>

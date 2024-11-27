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
            <p>Dear {{$employee->FirstName}},</p>
            <p>Congratulations on being selected for the position of <strong>{{$employee->Position}}</strong> at Samahang Nayon Hotel.</p>
            <p>Your account has been created, and you can now access our system using the following credentials:</p>
            <p><strong>Email:</strong> {{$employee->email}} </p>
            <p><strong>Password:</strong>{{$defaultPassword}}</p>
            <p>For your security, please change your password after logging in for the first time.</p>
            <p>Please click the button below to verify your account:</p>
            <p style="text-align: center; color: #f4f4f4">
                <a href="{{ url('/verify-user?token=' . $employee->verification_token) }}" class="verify-button">Verify Account</a>
            </p>
            <p>If you have any questions, feel free to reach out to us.</p>
            <p>We look forward to working with you!</p>
        </div>
        <div class="footer">
            <p>Samahang Nayon Hotel</p>
            <p><a href="{{url('/')}}">Visit our website</a></p>
        </div>
    </div>
</body>

</html>

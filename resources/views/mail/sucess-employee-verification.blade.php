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
            <h1>Account Verified Successfully!</h1>
        </div>
        <div class="content">
            <p>Dear {{$employee->FirstName}},</p>
            <p>We are pleased to inform you that your account verification at Samahang Nayon Hotel was successful!</p>
            <p>You can now log in and enjoy full access to our system using your credentials:</p>
            <p><strong>Email:</strong> {{$employee->email}}</p>
            <p>If you have not yet changed your password, we recommend doing so for your security.</p>
            <p>If you have any questions or need further assistance, feel free to contact us.</p>
            <p>Thank you for being part of Samahang Nayon Hotel. We look forward to providing you with the best experience!</p>
        </div>
        <div class="footer">
            <p>Samahang Nayon Hotel</p>
            <p><a href="{{url('/')}}">Visit our website</a></p>
        </div>
    </div>
</body>

</html>

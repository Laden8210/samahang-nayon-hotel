<style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f8f8;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        background-color: #007bff;
        color: #fff;
        padding: 10px 0;
        text-align: center;
    }

    .content {
        padding: 20px;
    }

    .footer {
        text-align: center;
        padding: 10px 0;
        color: #666;
        font-size: 12px;
    }

    .button {
        display: inline-block;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }

    .button:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your reservation has been successfully created. To finalize your reservation, please make a payment using the link below:</p>
            <a href="{{ $paymentLink }}" class="button">Pay Now</a>
            <p>If you have any questions or need assistance, please feel free to contact us.</p>
            <p>Thank you,</p>
            <p>The {{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

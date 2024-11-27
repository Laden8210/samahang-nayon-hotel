<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 5px;
            border: none;
        }
        .bold {
            font-weight: bold;
        }
        .payment-info {
            margin-top: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .services-table {
            margin-top: 20px;
            border-collapse: collapse;
        }
        .services-table th, .services-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .services-table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>FEDERATION OF SOCSARGEN SAMAHANG NAYON COOPERATIVE</h1>
            <p>Samahang Nayon Bldg. Corner Osmena-Roxas Streets, Zone II, Koronadal City</p>
            <p>NON VAT Reg TIN 004-416-359-000</p>
            <h2>OFFICIAL RECEIPT</h2>
            <p>(Hotel)</p>
        </div>

        <table>
            <tr>
                <td class="bold">Invoice No.</td>
                <td colspan="3"></td>
                <td class="bold">Amount</td>
                <td></td>
            </tr>
            <tr>
                <td class="bold">Total Sales</td>
                <td colspan="3"></td>
                <td class="bold">Less SCPWD Discount</td>
                <td></td>
            </tr>
            <tr>
                <td class="bold">Total Due</td>
                <td colspan="3"></td>
                <td class="bold">Less: Withholding Tax</td>
                <td></td>
            </tr>
            <tr>
                <td class="bold">Payment Due</td>
                <td colspan="3"></td>
            </tr>
        </table>

        <div class="payment-info">
            <p class="bold">Form of Payment:</p>
            <p>Bank Name:</p>
            <p>
                Cash <input type="checkbox">
                &nbsp;&nbsp;&nbsp; Check <input type="checkbox">
            </p>
        </div>

        <table>
            <tr>
                <td class="bold">Name:</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td class="bold">Address at:</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td class="bold">The sum of:</td>
                <td colspan="3"></td>
                <td class="bold">TIN:</td>
                <td></td>
            </tr>
            <tr>
                <td class="bold">Full payment of:</td>
                <td colspan="4"></td>
            </tr>
        </table>

        <div class="signature-section">
            <div>
                <span class="bold">Sr. Citizen TIN:</span> <span></span>
            </div>
            <div>
                <span class="bold">OSCA/PWD ID No.:</span> <span></span>
            </div>
            <div>
                <span class="bold">Signature:</span> <span></span>
            </div>
            <div>
                <span class="bold">By:</span> Cashier/Authorized Representative
            </div>
        </div>

        <table class="services-table">
            <tr>
                <th class="bold">PAYMENT OF THE FOLLOWING SERVICE/AMENITIES</th>
                <th class="bold">QUANTITY</th>
                <th class="bold">UNIT PRICE</th>
                <th class="bold">AMOUNT</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

</body>
</html>

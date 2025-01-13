<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusive Hotel Promo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007BFF;
            padding: 10px 20px;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            border-radius: 0 0 8px 8px;
            background-color: #ffffff;
            border: 1px solid #dbdbdb;
            padding: 20px;
            color: #333333;
        }
        .content h1 {
            font-size: 24px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .content .promo-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .promo-table th, .promo-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dddddd;
        }
        .promo-table th {
            background-color: #007BFF;
            color: #ffffff !important;
        }
        .promo-table td {
            background-color: #f4f4f4;
        }
        .cta-button {
            display: inline-block;
            padding: 15px 25px;
            background-color: #007BFF;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            margin: 20px 0;
        }
        .unsubscribe {
            margin-top: 40px;
            background-color: rgba(0, 0, 0, 0.05);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .unsubscribe p {
            margin: 10px 0;
            color: #666;
        }
        .unsubscribe a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }
        .unsubscribe a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{!! $title !!}</h1>
        </div>
        <div class="content">
            <h1>Hello, {{ $user }}!</h1>
            <table class="promo-table">
                <tr>
                    <td>Promo</td>
                    <td>{{ $promo->name }}</td>
                </tr>
                <tr>
                    <td>Room</td>
                    <td>{{ $promo->rooms->rooms }}</td>
                </tr>
                <tr>
                    <td>Minimum Stay</td>
                    <td>{{ $promo->minimum_stay }} Nights</td>
                </tr>
                <tr>
                    <td>Booking Period</td>
                    <td>{{ dateFormat($promo->book_periode_start) }} - {{ dateFormat($promo->book_periode_end) }}</td>
                </tr>
                <tr>
                    <td>Stay Period</td>
                    <td>{{ dateFormat($promo->periode_start) }} - {{ dateFormat($promo->periode_end) }}</td>
                </tr>
                <tr>
                    <td>Benefits</td>
                    <td>{!! $promo->benefits !!}</td>
                </tr>
            </table>
            @if ($bookingcode != 'none')
                <p>Booking code: <b>{{ $bookingcode }}</b></p>
                <p>Use this booking code to get an additional discount when booking accommodation services!</p>
                <p>{!! $suggestion !!}</p>
                <hr>
            @endif
            <p>Ready to book your stay? Click the button below to take advantage of these special offers:</p>
            <a href="{{ $link }}" class="cta-button">Book Now</a>
        </div>
    </div>
    <div class="unsubscribe">
        <p>Sign up now and enjoy exclusive promotions from our travel services delivered straight to your inbox!</p>
        <p>By registering, you'll always get the best deals for your dream vacation.</p>
        <p><a href="http://online.balikamitour.com">Click here</a> to join and start your adventure!</p>
        <p>Thank you for choosing Bali Kami Tour & Wedding. We look forward to welcoming you soon!</p>
    </div>
</body>
</html>

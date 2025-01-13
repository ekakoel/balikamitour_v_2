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
            color: #ffffff;
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
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
        }
        .unsubscribe a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{!! $title !!}</h1>
        </div>
        <div class="content">
            {{-- <h1>Hello, Agent!</h1> --}}
            <h1>Hello, {{ $user->name }}!</h1>
            <p>{!! $suggestion !!}</p>
            <table class="promo-table">
                {{-- <tr>
                    <th>Offer</th>
                    <th>Details</th>
                </tr> --}}
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
            <p>Ready to book your stay? Click the button below to take advantage of these special offers:</p>
            <a href="{{ $link }}" class="cta-button">Book Now</a>
            {{-- <p>If you no longer wish to receive these emails, you can <a href="#">unsubscribe</a> at any time.</p> --}}
            <p>If you no longer wish to receive these emails, you can <a href="{{ route('unsubscribe', ['email' => $user->email]) }}">unsubscribe</a> at any time.</p>
        </div>
    </div>
    <div class="unsubscribe">
        <p>Thank you for choosing Bali Kami Tour & Wedding. We look forward to welcoming you soon!</p>
    </div>
</body>
</html>

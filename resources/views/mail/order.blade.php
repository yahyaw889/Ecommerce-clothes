<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exclusive Offer from {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            font-size: 28px;
        }
        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .text-white{
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>WELCOME TO ARTIVA STORE
        </h1>
        <p>Dear {{ $order->first_name }} {{ $order->last_name }} 😎,</p>
        <p><strong>[ ARTIVA STORE ]</strong> offers a wide range of stylish and high-quality printed t-shirts. From classic designs to trendy prints, we have something for everyone.
        </p>
        <p>
            High-quality materials: Our t-shirts are made from premium materials for comfort and durability.
            Vibrant prints: Our printing technology ensures vibrant and long-lasting designs.
            Custom designs: Need a custom design? We can bring your ideas to life.
            Fast shipping: We offer fast and reliable shipping to ensure you receive your order quickly.
            Explore our collection today and discover your new favorite t-shirt!

            </p>
            <br>
            <br>
            <p>We look forward to see you! 👌👌</p>
        <p><a href="{{url('/')}}" class="cta-button text-white">Shop Now</a></p>
        <div class="footer">
            <p>Best regards 😍,</p>
            <p>{{ $order->first_name }}</p>
            <p>If you have any questions, feel free to contact us at {{ $social->email }}.</p>
        </div>
    </div>
</body>
</html>

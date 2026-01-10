<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                background-color: #f9f9f9;
                color: #333;
                padding: 20px;
            }

            .container {
                max-width: 600px;
                margin: auto;
                background: #ffffff;
                border: 1px solid #e0e0e0;
                padding: 20px;
                border-radius: 6px;
            }

            .header {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .section-title {
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
            }

            .order-detail {
                background-color: #f3f3f3;
                padding: 10px;
                border-radius: 4px;
            }

            .footer {
                font-size: 12px;
                color: #777;
                margin-top: 30px;
            }

            a {
                color: #2d6cdf;
            }

            .button {
                display: inline-block;
                background-color: #1a73e8;
                color: white;
                text-decoration: none;
                padding: 12px 20px;
                border-radius: 4px;
                margin: 20px 0;
            }
        </style>
    </head>

    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>

</html>

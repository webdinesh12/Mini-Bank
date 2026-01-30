<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        h1 {
            color: #333333;
            font-size: 24px;
        }

        p {
            line-height: 1.6;
            font-size: 16px;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        .footer {
            font-size: 12px;
            color: #888888;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        {!! $html !!}
    </div>
</body>

</html>

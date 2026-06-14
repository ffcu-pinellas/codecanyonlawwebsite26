<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            color: #333;
            line-height: 1.6;
            margin: 40px;
            padding: 0;
            background-color: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 26px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 10px 0;
            color: #111;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        .document-title {
            text-align: center;
            margin: 40px 0;
        }
        .document-title h2 {
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #333;
            display: inline-block;
            padding-bottom: 5px;
        }
        .content {
            font-size: 15px;
            text-align: justify;
            margin-bottom: 50px;
        }
        .content p {
            margin-bottom: 20px;
            text-indent: 30px;
        }
        .content h3, .content h4 {
            text-indent: 0;
            font-size: 16px;
            margin-top: 30px;
            text-transform: uppercase;
        }
        .print-btn-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        .print-btn {
            background-color: #2c5364;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif;
            font-weight: 600;
        }
        @media print {
            .print-btn-container {
                display: none;
            }
            body {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">{{ __('Print Document') }}</button>
    </div>

    @include('backend.layouts.letterhead')

    <div class="document-title">
        <h2>{{ $title }}</h2>
    </div>

    <div class="content">
        {!! $content !!}
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>

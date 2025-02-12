<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ config('app.name') }}</title>
    <style>
        body{
            font-family: 'Nunito', sans-serif;
            background-color: #f8fafc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-wrapper{
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.7;
            background-color: #f8fafc;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            border: 1px solid #dddddd;
            overflow: hidden;
        }
        .email-header{
            background-color: #735DA5;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        .email-header h1{
            margin: 0;
            text-align: center;
            color: #fff;
            font-size: 32px;
            fontweight: 400;
        }
	    .email-body{
	        color: #333;
	        line-height: 1.7;
            margin: 15px auto;
            padding: 10px;
	    }
        .email-footer{
            background-color: #735DA5;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            font-size: 12px
        }
       
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
                }
            }

            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
            }
        }


    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <div class="email-body">
                {{ $slot }}
            </div>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
    
</body>
</html>
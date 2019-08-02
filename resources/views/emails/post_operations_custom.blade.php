<!DOCTYPE html>
<html>
<head>
    <style>
        header, footer{
            text-align: center;
            color: #bbbfc3;
        }
        header a{
            color: #bbbfc3;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
        }
        .content{
            margin-left: 33%;
            margin-right: 33%;
        }
        .btn-right{
            text-align: center;
        }
        footer{
            font-size: 12px;
        }
        .button {
            background-color: #008CBA;
            border: none;
            color: white;
            padding: 8px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
        }

    </style>
</head>
<body>
<header>
    <br>
    <a href="http://blog.test/post">{{ config('app.name') }}</a>
    <br><br>
</header>
<hr>
<div class="content">
    <h4> Hello, {{ $auth }}</h4>
    <p>The "{{ $post->title }}" post was {{ $operation }} successfully.</p><br>
    <div class="btn-right">
        <a href={{ $btn_link }} class="button">{{ $button }}</a>
    </div>
    <p>
        Thanks,<br>
        Laravel
    </p>
</div>
<hr><br>
<footer>
    © 2019 {{ config('app.name') }}. All rights reserved.
</footer>
</body>
</html>

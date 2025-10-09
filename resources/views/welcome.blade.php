<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
             body {
                 margin: 0;
                 padding: 0;
                 font-family: 'Arial', sans-serif;
                 min-height: 100vh;
             }
             .header-container {
                 background-color: #4c3489;
                 border-radius: 0 0 30px 30px;
                 box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                 width: 70%;
                 margin: 0 auto;
                 text-align: center;
                 padding: 2rem 0;
             }
             .logo {
                 max-width: 300px;
                 height: auto;
                 margin-bottom: 1rem;
             }
             .app-name {
                 font-size: 2rem;
                 font-weight: bold;
                 color: white;
                 margin: 0;
             }
         </style>
    </head>
    <body>
        <div class="header-container">
            <img src="{{ asset('assets/img/logo_navbar.png') }}" alt="Logo" class="logo">
            <div class="app-name">
                Welcome to {{ config('app.name', '') }}
            </div>
        </div>
    </body>
</html>

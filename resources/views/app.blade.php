<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="6hZus_hKtAfltqFb5vDctU3nRP4IzYSvZ9pONS2nDb4" />
        <script>window.Laravel = { csrfToken: '{{ csrf_token() }}'};</script>

        <title>Track My Money | The most useful income-expense tracker</title>
        @vite('resources/css/app.css')
    </head>
    <body class="antialiased">
        <div id="app"></div>

        @vite('resources/js/app.js')
    </body>
</html>

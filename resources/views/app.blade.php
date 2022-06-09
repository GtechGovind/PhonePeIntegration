<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/app.js') }}" defer></script>
        <script src="{{ asset('/js/phonepe/bundle.js') }}"></script>
        <script src="https://kit.fontawesome.com/54bfa59fb9.js" crossorigin="anonymous"></script>
        <title>Mumbai Metro One</title>
        @inertiaHead
    </head>
    <body class="bg-gray-100 antialiased subpixel-antialiased">
        <div class="container mx-auto mt-2">
            @inertia
        </div>
        <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>
    </body>
</html>

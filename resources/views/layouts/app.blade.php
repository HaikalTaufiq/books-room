<!DOCTYPE html>

<html lang="en">

<head>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png" style="width: 150px;">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Booking Room')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dashboard/partial.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const dashboardContainer = document.querySelector('.dashboard-container');

            hamburgerBtn.addEventListener('click', function() {
                dashboardContainer.classList.toggle('sidebar-collapsed');
            });
        });
    </script>

</head>

<body onload="slideInPage()" style="background-color: #f8f7f7;">
    <div class="dashboard-container">


        @include('partials.topbar')
        <main class="main-content" id="pageContent">

            @yield('content')
        </main>
    </div>

    @yield('script')
</body>

<script>
    function slideInPage() {
        document.getElementById('pageContent').classList.add('slide-in-page');
    }
</script>

</html>
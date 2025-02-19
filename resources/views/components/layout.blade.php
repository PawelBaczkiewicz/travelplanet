<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('page-title', 'Travel Planet initial task')</title>

        <style>
            .alert {
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .alert-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>

    </head>
    <body>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div {{ $attributes }} >{{ $slot }}</div>

        <script>
            // for presentation - timezone conversion
            document.addEventListener("DOMContentLoaded", function() {
                const utcDates = document.querySelectorAll('.utc-date');

                utcDates.forEach(element => {
                    const utcDateStr = element.textContent.trim();
                    const localDate = new Date(utcDateStr + " UTC");
                    const formattedDate = localDate.toLocaleString();
                    element.textContent = formattedDate;
                });
            });
        </script>

        <script>
            // for forms - added timezone to input
            document.addEventListener("DOMContentLoaded", function() {
                var clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

                var timezoneInput = document.getElementById('user_timezone');
                if (timezoneInput) {
                    timezoneInput.value = clientTimezone;
                }
            });
        </script>

        <script>
            // for forms - datetime-local timezone conversion
            document.addEventListener("DOMContentLoaded", function() {
                const dateTimeInputs = document.querySelectorAll('input[type="datetime-local"]');

                dateTimeInputs.forEach(input => {

                    if (input.value) {
                        const utcDateStr = input.value;
                        const localDate = new Date(utcDateStr + "Z");
                        const localDateStr = localDate.toLocaleString('sv-SE').replace(' ', 'T').slice(0, 16);
                        input.value = localDateStr;
                    }
                });
            });
        </script>

    </body>
</html>



<x-admin-layout>
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #reservation-table {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #reservation-table table {
            width: 100%;
            border-collapse: collapse;
        }

        #reservation-table th,
        #reservation-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        #reservation-table th {
            background-color: #f2f2f2;
        }
    </style>
    <div id="calendar"></div>
    <div id="reservation-table" class="mt-4">
        <h2>Reservations for {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Guest Name</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    @if (\Carbon\Carbon::parse($reservation->check_in)->isSameMonth(\Carbon\Carbon::now()))
                        <tr>
                            <td>{{ $reservation->room->room_number }}</td>
                            <td>{{ $reservation->name }}</td>
                            <td>{{ $reservation->check_in }}</td>
                            <td>{{ $reservation->check_out }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: [
                    @foreach ($reservations as $reservation)
                        {
                            title: 'Room: {{ $reservation->room->name }}',
                            start: '{{ $reservation->check_in }}',
                            end: '{{ $reservation->check_out }}',
                            description: 'Reservation for {{ $reservation->name }}'
                        },
                    @endforeach
                ],
                eventRender: function(event, element) {
                    element.qtip({
                        content: event.description
                    });
                }
            });
        });
    </script>
</x-admin-layout>

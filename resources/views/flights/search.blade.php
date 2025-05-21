<!DOCTYPE html>
<html>
<head>
    <title>Flight Results</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        tbody tr:hover {
            background-color: #fafafa;
        }
    </style>
</head>
<body>

    <h1>Flight Results</h1>

    @if(isset($error))
        <p style="color:red;">{{ $error }}</p>
    @endif

    @if(isset($flights) && count($flights) > 0)
        <table>
            <thead>
                <tr>
                    <th>Flight ID</th>
                    <th>Price</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flights as $flight)
                    <tr>
                        <td>{{ $flight['id'] ?? 'N/A' }}</td>
                        <td>{{ $flight['price']['total'] ?? 'N/A' }} {{ $flight['price']['currency'] ?? '' }}</td>
                        <td>
                            {{ $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? '' }}<br>
                            {{ $flight['itineraries'][0]['segments'][0]['departure']['at'] ?? '' }}
                        </td>
                        <td>
                            {{ $flight['itineraries'][0]['segments'][0]['arrival']['iataCode'] ?? '' }}<br>
                            {{ $flight['itineraries'][0]['segments'][0]['arrival']['at'] ?? '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No flights found.</p>
    @endif

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Countries List</title>
</head>
<body>
    <h1>Countries</h1>

    @if(!empty($countries))
        <ul>
            @foreach($countries as $country)
                <li>{{ $country['name']['common'] ?? 'Unknown' }} ({{ $country['cca2'] ?? '' }})</li>
            @endforeach
        </ul>
    @else
        <p>No countries found.</p>
    @endif

</body>
</html>
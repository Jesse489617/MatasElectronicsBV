<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $purchase->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h1 { font-size: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
<h1>Invoice #{{ $purchase->id }}</h1>
<p><strong>User:</strong> {{ $user->name }} ({{ $user->email }})</p>
<p><strong>Date:</strong> {{ $purchase->created_at->format('d/m/Y') }}</p>

<h2>Assembly: {{ $assembly->name }}</h2>
<p><strong>Price:</strong> €{{ $assembly->price }}</p>

@if($components->count())
    <h3>Components</h3>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($components as $index => $component)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $component->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<p class="total">Total: €{{ $assembly->price }}</p>
</body>
</html>

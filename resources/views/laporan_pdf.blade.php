<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1f2937; }
        .header { margin-bottom: 16px; border-bottom: 2px solid #1e3a8a; padding-bottom: 8px; }
        .header h2 { margin: 0 0 4px 0; color: #1e3a8a; }
        .header p { margin: 0; font-size: 10px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e3a8a; color: #ffffff; text-align: left; padding: 6px 8px; font-size: 10px; }
        td { border: 1px solid #d1d5db; padding: 6px 8px; font-size: 10px; }
        tr:nth-child(even) { background-color: #f9fafb; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DHD Interior — {{ $title }}</h2>
        <p>Periode: {{ $periodText }} | Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($headings as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headings) }}" style="text-align:center;">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
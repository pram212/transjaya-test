<table>
    <thead>
        <tr>
            <th colspan="{{ count($allDates) + 1 }}">LAPORAN LABA RUGI</th>
        </tr>
        <tr>
            <th>Kategori</th>
            @foreach ($allDates as $date)
                <th>{{ date('d/m/Y', strtotime($date)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="{{ count($allDates) + 1 }}">Income</th>
        </tr>
        @foreach ($income['data'] as $item)
            <tr>
                <td>{{ $item['category'] }}</td>
                @foreach ($allDates as $date)
                    <td>{{ number_format(@$item[$date], 0, ',', '.') }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr>
            <th colspan="{{ count($allDates) + 1 }}">Expense</th>
        </tr>
        @foreach ($expense['data'] as $item)
            <tr>
                <td>{{ $item['category'] }}</td>
                @foreach ($allDates as $date)
                    <td>{{ number_format(@$item[$date], 0, ',', '.') }}</td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th>Net Income</th>
            @foreach ($allDates as $date)
                <th>{{ number_format($netIncome[$date], 0, ',', '.') }}</th>
            @endforeach
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="{{ count($allMonth) + 1 }}">LAPORAN LABA RUGI</th>
        </tr>
        <tr>
            <th rowspan="2" class="align-middle">Kategori</th>
            @foreach ($allMonth as $month)
                <th>{{ date('m/Y', strtotime($month)) }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($allMonth as $month)
                <th>Amount</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="{{ count($allMonth) + 1 }}">Income</th>
        </tr>
        @foreach ($income['data'] as $item)
            <tr>
                <td>{{ $item['category'] }}</td>
                @foreach ($allMonth as $month)
                    <td>{{ number_format(@$item[$month], 2, ',', '.') }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr>
            <th colspan="{{ count($allMonth) + 1 }}">Expense</th>
        </tr>
        @foreach ($expense['data'] as $item)
            <tr>
                <td>{{ $item['category'] }}</td>
                @foreach ($allMonth as $month)
                    <td>{{ number_format(@$item[$month], 2, ',', '.') }}</td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th>Net Income</th>
            @foreach ($allMonth as $month)
                <th>{{ number_format($netIncome[$month], 2, ',', '.') }}</th>
            @endforeach
        </tr>
    </tbody>
</table>

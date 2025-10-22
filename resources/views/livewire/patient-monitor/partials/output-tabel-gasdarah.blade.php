<div class="bg-white p-5 rounded-2xl shadow-md overflow-x-auto border border-gray-200">
    <h3 class="text-xl font-bold mb-5 border-b-2 pb-2 text-blue-900 tracking-wide">
        Riwayat Gas Darah
    </h3>

    @php
        $params = [
            'Gula Darah' => 'gula_darah',
            'pH' => 'ph',
            'PCO₂' => 'pco2',
            'PO₂' => 'po2',
            'HCO₃' => 'hco3',
            'BE' => 'be',
            'SaO₂' => 'sao2'
        ];
    @endphp

    <table class="min-w-full border-collapse text-sm">
    <thead class="bg-blue-700 text-white text-xs uppercase sticky top-0">
        <tr>
            <th class="px-4 py-3 text-left w-40">Parameter</th>
            @foreach ($bloodGasResults as $result)
                <th class="px-4 py-1 text-center">
                    {{ \Carbon\Carbon::parse($result->taken_at)->format('H:i') }}<br>
                    <span class="text-xs text-gray-200">{{ $result->author_name }}</span>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($params as $label => $field)
            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                <td class="px-4 py-2 font-semibold">{{ $label }}</td>
                @foreach ($bloodGasResults as $result)
                    @php
                        $value = $result->$field;
                        $badge = '';
                        if($field == 'gula_darah') $badge = $value > 140 ? 'bg-red-100 text-red-700 font-semibold' : 'bg-green-100 text-green-800';
                        elseif($field == 'ph') $badge = ($value < 7.35 || $value > 7.45) ? 'bg-red-100 text-red-700 font-semibold' : 'bg-green-100 text-green-800';
                        elseif($field == 'sao2') $badge = $value < 95 ? 'bg-red-100 text-red-700 font-semibold' : 'bg-green-100 text-green-800';
                    @endphp
                    <td class="px-4 py-2 text-center {{ $badge }}">{{ $value ?? '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

</div>

<style>
/* Scrollbar tipis & halus */
::-webkit-scrollbar {
    height: 6px;
}
::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.4);
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background-color: rgba(107, 114, 128, 0.6);
}
</style>

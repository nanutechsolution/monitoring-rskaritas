<div class="p-4 border rounded-md shadow-sm bg-white">
    <h2 class="text-xl font-semibold mb-3">Grid Observasi 24 Jam</h2>
    <div classs="block w-full overflow-x-auto" wire:poll.30s="loadCycles">

        <table class="min-w-full text-sm border border-gray-300 divide-y divide-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-2 py-2 text-left text-gray-700 border-r" style="min-width: 150px;">Parameter</th>

                    {{-- Loop untuk Header Jam --}}
                    @foreach ($hours as $hour)
                    <th class="px-2 py-2 font-medium text-center text-gray-700 border-r">{{ $hour }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                {{-- Baris untuk Tanda Vital --}}
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Temp. Inkubator</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->temp_inkubator ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Temp. Skin</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->temp_skin ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Heart Rate</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->heart_rate ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Resp. Rate</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->respiratory_rate ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Tekanan Darah</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->tekanan_darah ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Sat. O2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->sat_o2 ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Irama EKG</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->irama_ekg ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Skala Nyeri</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->skala_nyeri ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Huidifier Inkubator</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->huidifier_inkubator ?? '' }}</td>
                    @endforeach
                </tr>

                {{-- Baris untuk Observasi (Boolean) --}}
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">Cyanosis</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->cyanosis ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">Pucat</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->pucat ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">Icterus</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->icterus ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">CRT < 2 dtk</td>
                            @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->crt_lt_2 ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">Bradikardia</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->bradikardia ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-2 py-1 font-medium border-r">Stimulasi</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ ($cycles[$hour]->stimulasi ?? false) ? '+' : '' }}</td>
                    @endforeach
                </tr>

                {{-- Header Section Ventilator --}}
                <tr class="bg-gray-200">
                    <td class="px-2 py-1 font-bold border-r" colspan="{{ count($hours) + 1 }}">TERAPI OKSIGEN / VENTILATOR</td>
                </tr>
                <tr>
                    <td class="px-2 py-1 font-medium border-r">Mode</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_mode ?? '' }}</td>
                    @endforeach
                </tr>

                {{-- Setting Nasal --}}
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Nasal: FiO2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_fio2_nasal ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Nasal: Flow</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_flow_nasal ?? '' }}</td>
                    @endforeach
                </tr>

                {{-- Setting CPAP --}}
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">CPAP: FiO2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_fio2_cpap ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">CPAP: Flow</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_flow_cpap ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">CPAP: PEEP</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_peep_cpap ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">CPAP: FiO2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_fio2_cpap ?? '' }}</td>
                    @endforeach
                </tr>
                {{-- Setting HFO --}}
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">HFO: FiO2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_fio2_hfo ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">HFO: Frekuensi</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_frekuensi_hfo ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">HFO: MAP</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_map_hfo ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">HFO: Amplitudo</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_amplitudo_hfo ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">HFO: I:T</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_it_hfo ?? '' }}</td>
                    @endforeach
                </tr>

                {{-- Setting Mekanik --}}
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: Mode</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_mode_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: FiO2</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_fio2_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: PEEP</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_peep_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: PIP</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_pip_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: TV/Vte</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_tv_vte_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: RR/Spontan</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_rr_spontan_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: P. Max</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_p_max_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-1 font-light border-r pl-4">Mekanik: I:E</td>
                    @foreach ($hours as $hour)
                    <td class="px-2 py-1 text-center border-r">{{ $cycles[$hour]->vent_ie_mekanik ?? '' }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

@props(['pippAssessments'])

@php
    use Carbon\Carbon;
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3 text-primary-700 dark:text-primary-300">
            Riwayat Penilaian Nyeri (PIPP)
        </h3>

        <div class="overflow-x-auto mt-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-700">
            <table class="min-w-full divide-y-2 divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                <thead class="text-left bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Jam</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Usia Gest</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Perilaku</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">HR Max</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">SpO2 Min</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Alis</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Mata</th>
                        <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Nasolabial</th>
                        <th class="px-2 py-2 font-bold text-gray-700 dark:text-gray-300">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($pippAssessments as $score)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                        <th class="px-2 py-2 text-gray-900 dark:text-gray-100 font-medium">
                            {{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}<br>
                            <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $score->author_name }}</span>
                        </th>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->gestational_age }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->behavioral_state }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->max_heart_rate }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->min_oxygen_saturation }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->brow_bulge }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->eye_squeeze }}</td>
                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->nasolabial_furrow }}</td>

                        <td class="px-2 py-2 text-center font-bold text-lg
                                   {{ $score->total_score > 12 ? 'text-danger-600 dark:text-danger-400' : ($score->total_score > 6 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                            {{ $score->total_score }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center p-4 text-gray-500 dark:text-gray-400">Belum ada penilaian PIPP.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

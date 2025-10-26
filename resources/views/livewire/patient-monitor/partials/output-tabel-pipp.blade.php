  <div class="bg-white overflow-hidden shadow-sm ">
      <div class="p-6 text-gray-900">
          <h3 class="text-lg font-medium border-b pb-3">Riwayat Penilaian Nyeri (PIPP)</h3>
          <div class="overflow-x-auto mt-4">
              <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                  <thead class="text-left">
                      <tr>
                          <th class="px-2 py-2">Jam</th>
                          <th class="px-2 py-2">Usia Gest</th>
                          <th class="px-2 py-2">Perilaku</th>
                          <th class="px-2 py-2">HR Max</th>
                          <th class="px-2 py-2">SpO2 Min</th>
                          <th class="px-2 py-2">Alis</th>
                          <th class="px-2 py-2">Mata</th>
                          <th class="px-2 py-2">Nasolabial</th>
                          <th class="px-2 py-2 font-bold">Total</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">@forelse ($pippAssessments as $score) <tr>
                          <th class="px-2 py-2">
                              {{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}<br>
                              <span class="text-xs text-gray-400">{{ $score->author_name }}</span>
                          </th>

                          <td class="px-2 py-2 text-center">{{ $score->gestational_age }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->behavioral_state }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->max_heart_rate }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->min_oxygen_saturation }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->brow_bulge }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->eye_squeeze }}</td>
                          <td class="px-2 py-2 text-center">{{ $score->nasolabial_furrow }}</td>
                          <td class="px-2 py-2 text-center font-bold text-lg {{ $score->total_score > 12 ? 'text-red-600' : ($score->total_score > 6 ? 'text-yellow-600' : 'text-green-600') }}">{{ $score->total_score }}</td>
                      </tr> @empty <tr>
                          <td colspan="9" class="text-center p-4 text-gray-500">Belum ada penilaian PIPP.</td>
                      </tr> @endforelse</tbody>
              </table>
          </div>
      </div>
  </div>

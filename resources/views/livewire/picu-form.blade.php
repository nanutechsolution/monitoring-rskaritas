<div>
    <livewire:picu-patient-header :regPeriksa="$regPeriksa" :monitoringSheet="$monitoringSheet" :key="'header-'.$monitoringSheet->id" />

    <livewire:picu-sheet-notes :monitoringSheet="$monitoringSheet" :key="'notes-'.$monitoringSheet->id" />

    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
            {{-- Tab 1 --}}
            <button wire:click="$set('activeTab', 'observasi')" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                           {{ $activeTab == 'observasi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Observasi (TTV & Grid)
            </button>

            {{-- Tab 2 --}}
            <button wire:click="$set('activeTab', 'cairan')" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                           {{ $activeTab == 'cairan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Keseimbangan Cairan
            </button>

            {{-- Tab 3 --}}
            <button wire:click="$set('activeTab', 'medikasi')" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                           {{ $activeTab == 'medikasi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Medikasi & AGD
            </button>

            {{-- Tab 4 --}}
            <button wire:click="$set('activeTab', 'alat')" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                           {{ $activeTab == 'alat' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Alat Terpasang
            </button>

            {{-- Tab 5 --}}
            <button wire:click="$set('activeTab', 'cppt')" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm
                           {{ $activeTab == 'cppt' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                CPPT (SOAP)
            </button>
        </nav>
    </div>

    {{-- =============================================== --}}
    {{-- === 3. KONTEN TAB (Dinamis) === --}}
    {{-- =============================================== --}}
    <div class="mt-6">

        @if ($activeTab == 'observasi')
        {{-- Hanya di-load jika tab 'observasi' aktif --}}
        <div>
            <h3 class="mb-3 text-lg font-semibold">Input Real-Time (Tampilan 1)</h3>
            <livewire:picu-input-realtime :monitoringSheetId="$monitoringSheet->id" :key="'input-'.$monitoringSheet->id" />
        </div>
        <hr class="my-6 border-t-2 border-gray-200">
        <div>
            <livewire:picu-vital-chart :monitoringSheetId="$monitoringSheet->id" :key="'chart-'.$monitoringSheet->id" />
        </div>
        <hr class="my-6 border-t-2 border-gray-200">

        <div>
            <h3 class="mb-3 text-lg font-semibold">Grid Review 24 Jam (Tampilan 2)</h3>
            <livewire:picu-review-grid :monitoringSheetId="$monitoringSheet->id" :key="'grid-'.$monitoringSheet->id" />
        </div>
        <hr class="my-6 border-t-2 border-gray-200">

        <div>
            <h3 class="mb-3 text-lg font-semibold">Grid Review 24 Jam (Tampilan 2)</h3>
            <livewire:picu-review-grid :monitoringSheetId="$monitoringSheet->id" :key="'grid-'.$monitoringSheet->id" />
        </div>


        @endif

        @if ($activeTab == 'cairan')
        {{-- Hanya di-load jika tab 'cairan' aktif --}}
        <div>
            <h3 class="mb-3 text-lg font-semibold">Nutrisi & Keseimbangan Cairan</h3>
            <livewire:picu-fluid-balance :monitoringSheetId="$monitoringSheet->id" :key="'fluid-'.$monitoringSheet->id" lazy />
        </div>
        @endif

        @if ($activeTab == 'medikasi')
        {{-- Hanya di-load jika tab 'medikasi' aktif --}}
        <div>
            <h3 class="mb-3 text-lg font-semibold">Obat-obatan</h3>
            <livewire:picu-medications :monitoringSheetId="$monitoringSheet->id" :key="'meds-'.$monitoringSheet->id" lazy />
        </div>
        <hr class="my-6 border-t-2 border-gray-200">
        <div>
            <h3 class="mb-3 text-lg font-semibold">Blood Gas Monitor (AGD)</h3>
            <livewire:picu-blood-gas :monitoringSheetId="$monitoringSheet->id" :key="'agd-'.$monitoringSheet->id" lazy />
        </div>
        @endif

        @if ($activeTab == 'alat')
        <div>
            <h3 class="mb-3 text-lg font-semibold">Alat Terpasang</h3>
            <livewire:picu-devices :monitoringSheetId="$monitoringSheet->id" :key="'devices-'.$monitoringSheet->id" lazy />
        </div>
        @endif

        @if ($activeTab == 'cppt')
        <div>
            <h3 class="mt-8 mb-3 text-lg font-semibold">Riwayat CPPT (Data dari Khanza)</h3>
            <livewire:picu-cppt-viewer :noRawat="$noRawat" :key="'cppt-viewer-'.$monitoringSheet->id" lazy />
        </div>
        @endif

        </di>

        <div class="p-2 mt-6 text-sm text-gray-500 bg-gray-100 rounded-md">
            Debug: Aktif Sheet ID: {{ $monitoringSheet->id }} | Status: {{ $isToday ? 'Lembar Hari Ini' : 'Lembar Riwayat' }}
        </div>
    </div>

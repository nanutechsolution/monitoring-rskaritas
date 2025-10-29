<div>

    <livewire:picu-patient-header :regPeriksa="$regPeriksa" :monitoringSheet="$monitoringSheet" :key="'header-'.$monitoringSheet->id" />

    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Catatan Klinis Harian</h3>

        <livewire:picu-sheet-notes :monitoringSheet="$monitoringSheet" :key="'notes-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">

    <div>
        <h3 class="mb-3 text-lg font-semibold">Input Real-Time (Tampilan 1)</h3>
        <livewire:picu-input-realtime :monitoringSheetId="$monitoringSheet->id" :key="'input-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Grid Review 24 Jam (Tampilan 2)</h3>
        <livewire:picu-review-grid :monitoringSheetId="$monitoringSheet->id" :key="'grid-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Blood Gas Monitor (AGD)</h3>

        <livewire:picu-blood-gas :monitoringSheetId="$monitoringSheet->id" :key="'agd-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Nutrisi & Keseimbangan Cairan</h3>
        <livewire:picu-fluid-balance :monitoringSheetId="$monitoringSheet->id" :key="'fluid-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Obat-obatan</h3>
        <livewire:picu-medications :monitoringSheetId="$monitoringSheet->id" :key="'meds-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">Alat Terpasang</h3>
        <livewire:picu-devices :monitoringSheetId="$monitoringSheet->id" :key="'devices-'.$monitoringSheet->id" />
    </div>
    <hr class="my-6 border-t-2 border-gray-200">
    <div>
        <h3 class="mb-3 text-lg font-semibold">CPPT (Data dari Khanza)</h3>
        <livewire:picu-cppt-viewer :noRawat="$noRawat" :key="'cppt-viewer-'.$monitoringSheet->id" />
    </div>
</div>

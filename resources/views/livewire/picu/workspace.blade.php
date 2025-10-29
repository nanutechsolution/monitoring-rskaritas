<div>
    {{--
      Ini adalah file livewire/picu/workspace.blade.php
      File ini adalah 'halaman' yang akan me-render komponen 'otak' kita (PicuForm).
    --}}

    {{-- Header Halaman (opsional, sesuaikan dengan layout Anda) --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Workspace Monitoring PICU
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @livewire('picu-form', [
                        'noRawat'   => $noRawatAsli,
                        'sheetDate' => $sheetDate
                    ], key($noRawatAsli . $sheetDate))

                </div>
            </div>
        </div>
    </div>
</div>

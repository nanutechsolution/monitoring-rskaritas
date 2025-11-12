<div>
    <!-- Tombol buka modal -->
    <button type="button" wire:click="$set('showTherapyModal', true)" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        Program Terapi
    </button>

    <!-- Modal -->
    @if($showTherapyModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Program Terapi & Instruksi Dokter
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Lihat riwayat di sebelah kiri, buat atau perbarui instruksi di sebelah kanan.
                </p>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid md:grid-cols-5 gap-6 h-full">

                    <!-- Riwayat Instruksi -->
                    <div class="md:col-span-2 max-h-[calc(80vh-150px)] overflow-y-auto pr-3 space-y-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-2 sticky top-0 bg-white dark:bg-gray-800 py-2">
                            Riwayat Instruksi
                        </h3>

                        @forelse($therapy_program_history as $program)
                        <div wire:click="loadHistoryToForm({{ $program->id }})" wire:loading.attr="disabled" class="border p-3 rounded-lg cursor-pointer transition hover:bg-gray-50 dark:hover:bg-gray-700
                            @if($loop->first) border-primary-500 ring-2 ring-primary-100 dark:ring-primary-900 @else border-gray-200 dark:border-gray-600 @endif">
                            <div class="flex justify-between items-center text-sm mb-2 pb-2 border-b dark:border-gray-600">
                                <span class="font-semibold text-primary-700 dark:text-primary-300">{{ $program->author_name }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $program->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="text-sm text-gray-800 dark:text-gray-200 space-y-2">
                                <div>
                                    <strong class="text-gray-600 dark:text-gray-400 block text-xs">Masalah Klinis:</strong>
                                    <p class="pl-2">{{ $program->masalah_klinis }}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-600 dark:text-gray-400 block text-xs">Program Terapi:</strong>
                                    <p class="pl-2">{{ $program->program_terapi }}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-600 dark:text-gray-400 block text-xs">Enteral:</strong>
                                    <p class="pl-2">{{ $program->nutrisi_enteral }}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-600 dark:text-gray-400 block text-xs">Parenteral:</strong>
                                    <p class="pl-2">{{ $program->nutrisi_parenteral }}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-600 dark:text-gray-400 block text-xs">Lab:</strong>
                                    <p class="pl-2">{{ $program->pemeriksaan_lab }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada riwayat program terapi.</p>
                        @endforelse
                    </div>

                    <!-- Form Program Terapi -->
                    <div class="md:col-span-3 space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200">Buat / Perbarui Instruksi</h3>
                            <button type="button" wire:click="resetForm" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-xs font-medium rounded border hover:bg-gray-200 dark:hover:bg-gray-600">
                                Reset / Tulis Baru
                            </button>
                        </div>

                        @error('currentCycleId')
                        <p class="text-sm text-red-600 dark:text-red-400 p-2 border rounded bg-red-50 dark:bg-red-900/50">{{ $message }}</p>
                        @enderror

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Masalah Klinis</label>
                                <textarea wire:model.defer="masalah" rows="3" class="w-full rounded border p-2 bg-white dark:bg-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600"></textarea>
                                @error('masalah_klinis')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Terapi</label>
                                <textarea wire:model.defer="program" rows="4" class="w-full rounded border p-2 bg-white dark:bg-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600"></textarea>
                                @error('program_terapi')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enteral</label>
                                    <textarea wire:model.defer="enteral" rows="3" class="w-full rounded border p-2 bg-white dark:bg-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600"></textarea>
                                    @error('nutrisi_enteral')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parenteral</label>
                                    <textarea wire:model.defer="parenteral" rows="3" class="w-full rounded border p-2 bg-white dark:bg-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600"></textarea>
                                    @error('nutrisi_parenteral')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lab</label>
                                <textarea wire:model.defer="lab" rows="3" class="w-full rounded border p-2 bg-white dark:bg-gray-700 dark:text-gray-100 border-gray-300 dark:border-gray-600"></textarea>
                                @error('pemeriksaan_lab')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t flex justify-end space-x-3">
                <button type="button" wire:click="$set('showTherapyModal', false)" class="px-4 py-2 bg-white dark:bg-gray-700 rounded border hover:bg-gray-100 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button type="button" wire:click="save" wire:loading.attr="disabled" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

            </div>

        </div>
    </div>
    @endif
</div>

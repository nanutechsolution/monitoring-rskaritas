  <form wire:submit="saveRecord" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
          <h3 class="text-lg font-medium border-b pb-3">Form Input Observasi</h3>
          <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700">Jam Observasi</label>
              <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm">
                  {{ \Carbon\Carbon::parse($record_time)->format('d M Y, H:i') }}
              </div>
          </div>
          <div class="border-b border-gray-200 mt-4">
              <nav class="bg-gray-50 shadow-sm -mb-px flex space-x-2 sm:space-x-4 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 px-2 py-1" aria-label="Tabs">
                  <button wire:click.prevent="$set('activeTab', 'observasi')" type="button" class="{{ $activeTab === 'observasi' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                      Observasi
                  </button>

                  <button wire:click.prevent="$set('activeTab', 'ventilator')" type="button" class="{{ $activeTab === 'ventilator' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                      Ventilator
                  </button>

                  <button wire:click.prevent="$set('activeTab', 'cairan')" type="button" class="{{ $activeTab === 'cairan' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                      Cairan
                  </button>
                  <button wire:click.prevent="$set('activeTab', 'lainnya')" type="button" class="{{ $activeTab === 'lainnya' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                      Lain-lain
                  </button>
              </nav>
          </div>
          <div class="space-y-4 mt-4">
              <div x-show="$wire.activeTab === 'observasi'" class="space-y-4">
                  @include('livewire.patient-monitor.partials.tab-input-observasi')
              </div>
              <div x-show="$wire.activeTab === 'ventilator'" class="space-y-4">
                  @include('livewire.patient-monitor.partials.tab-input-ventilator')
              </div>
              <div x-show="$wire.activeTab === 'cairan'" class="space-y-4">
                  @include('livewire.patient-monitor.partials.tab-input-cairan')
              </div>
              <div x-show="$wire.activeTab === 'lainnya'" class="space-y-4">
                  @include('livewire.patient-monitor.partials.tab-input-lainnya')
              </div>
          </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 space-y-3 border-t">
          <div class="text-right">
              <button type="submit" wire:loading.attr="disabled" @click="$dispatch('sync-repeaters')" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                  <span wire:loading.remove wire:target="saveRecord">Simpan Catatan</span>
                  <span wire:loading wire:target="saveRecord">Menyimpan...</span>
              </button>
          </div>
      </div>
  </form>

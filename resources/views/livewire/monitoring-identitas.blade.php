<div class="card shadow-sm p-4">
    <h4 class="mb-4">Form Identitas Pasien NICU</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="simpan">

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Bayi</label>
                <input type="text" wire:model="nama_bayi" class="form-control">
                @error('nama_bayi') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Ibu</label>
                <input type="text" wire:model="nama_ibu" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" wire:model="tanggal_lahir" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Umur Kehamilan</label>
                <input type="text" wire:model="umur_kehamilan" class="form-control" placeholder="Minggu">
            </div>
            <div class="col-md-4">
                <label class="form-label">Berat Badan Lahir</label>
                <input type="text" wire:model="bb_lahir" class="form-control" placeholder="Gram">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Diagnosa</label>
            <input type="text" wire:model="diagnosa" class="form-control">
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Dokter Penanggung Jawab</label>
                <input type="text" wire:model="dokter" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ruangan</label>
                <input type="text" wire:model="ruangan" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Nomor Register</label>
                <input type="text" wire:model="register" class="form-control">
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Simpan & Lanjutkan</button>
    </form>
</div>

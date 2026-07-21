<div class="card border border-light shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 text-primary fw-bold">Nisbah</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label font-weight-bold">
                    Nomor Surat <span class="text-danger">*</span>
                </label>

                <input type="text" id="nomor_surat" name="nomor_surat" class="form-control"
                    value="{{ old('nomor_surat', $surat_keluar->nomor_surat ?? '') }}" readonly required>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">
                    Cabang <span class="text-danger">*</span>
                </label>

                <select id="cabang" name="cabang" class="form-select" required>
                    <option value="">Pilih Cabang</option>

                    @php
                        $cabangList = [
                            'Pekanbaru',
                            'Dumai',
                            'Bengkalis',
                            'Bangkinang (Kabupaten Kampar)',
                            'Pangkalan Kerinci (Kabupaten Pelalawan)',
                            'Teluk Kuantan (Kabupaten Kuantan Singingi)',
                            'Pasir Pengaraian (Kabupaten Rokan Hulu)',
                            'Tembilahan (Kabupaten Indragiri Hilir)',
                            'Siak Sri Indrapura (Kabupaten Siak)',
                            'Selat Panjang (Kabupaten Kepulauan Meranti)',
                            'Tanjung Pinang',
                            'Tanjung Balai Karimun',
                            'Bintan',
                            'Ranai (Kabupaten Natuna)',
                            'Daik Lingga (Kabupaten Lingga)',
                            'Tarempa (Kabupaten Kepulauan Anambas)',
                        ];
                    @endphp

                    @foreach ($cabangList as $cabang)
                        <option value="{{ $cabang }}"
                            {{ old('cabang', $surat_keluar->cabang ?? '') == $cabang ? 'selected' : '' }}>
                            {{ $cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">Nama Nasabah <span class="text-danger">*</span></label>
                <input type="text" name="nama_nasabah" class="form-control"
                    value="{{ old('nama_nasabah', $surat_keluar->nama_nasabah ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">Jenis Nasabah <span class="text-danger">*</span></label>
                <input type="text" name="jenis_nasabah" class="form-control"
                    value="{{ old('jenis_nasabah', $surat_keluar->jenis_nasabah ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">Total Nominal (Rp) <span class="text-danger">*</span></label>
                <input type="number" id="total_nominal" name="total_nominal" class="form-control"
                    value="{{ old('total_nominal', $surat_keluar->total_nominal ?? 0) }}" readonly required>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">Total Relation Outstanding <span
                        class="text-danger">*</span></label>
                <input type="number" name="total_relation_outstanding" class="form-control"
                    value="{{ old('total_relation_outstanding', $surat_keluar->total_relation_outstanding ?? '') }}"
                    required>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold">Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control"
                    value="{{ old('tanggal', $surat_keluar->tanggal ?? date('Y-m-d')) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label font-weight-bold">Alasan Pengajuan <span class="text-danger">*</span></label>
                <textarea name="alasan" class="form-control" rows="3" required>{{ old('alasan', $surat_keluar->alasan ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label font-weight-bold">
                    Lampiran (JPG, JPEG, PNG)
                </label>

                <input type="file" name="lampiran" class="form-control" accept=".jpg,.jpeg,.png,image/*">

                @if (isset($surat_keluar) && $surat_keluar->lampiran)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $surat_keluar->lampiran) }}" target="_blank">
                            <img src="{{ asset('storage/' . $surat_keluar->lampiran) }}" class="img-thumbnail"
                                style="max-height:180px;">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 text-primary fw-bold">Rekening Deposito</h6>
        <button type="button" class="btn btn-sm btn-primary btn-round" onclick="addRow()">
            <i class="fa fa-plus me-1"></i> Tambah Baris
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="itemsTable" style="min-width: 1300px;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 14%">No Rekening <span class="text-danger">*</span></th>
                        <th style="width: 12%">Nominal <span class="text-danger">*</span></th>
                        <th style="width: 10%">Jk. Waktu <span class="text-danger">*</span></th>
                        <th style="width: 11%">Tgl Penempatan</th>
                        <th style="width: 11%">Tgl Perpanjangan</th>
                        <th style="width: 11%">Tgl Jatuh Tempo <span class="text-danger">*</span></th>
                        <th style="width: 10%">Spesial Nisbah <span class="text-danger">*</span></th>
                        <th style="width: 10%">Expected Return <span class="text-danger">*</span></th>
                        <th style="width: 11%">Jenis Transaksi <span class="text-danger">*</span></th>
                        <th style="width: 5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $items = old('items', isset($surat_keluar) ? $surat_keluar->depositoItems->toArray() : [[]]);
                    @endphp

                    @foreach ($items as $index => $item)
                        <tr>
                            <td>
                                <input type="text" name="items[{{ $index }}][nomor_rekening_deposito]"
                                    class="form-control form-control-sm"
                                    value="{{ $item['nomor_rekening_deposito'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][nominal]"
                                    class="form-control form-control-sm nominal-input" min="0"
                                    value="{{ $item['nominal'] ?? '' }}" oninput="calculateTotalNominal()" required>
                            </td>
                            <td>
                                <select name="items[{{ $index }}][jangka_waktu]"
                                    class="form-select form-select-sm jangka-waktu" required>
                                    <option value="">Pilih</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }} Bulan"
                                            {{ ($item['jangka_waktu'] ?? '') == $i . ' Bulan' ? 'selected' : '' }}>
                                            {{ $i }} Bulan
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <input type="date" name="items[{{ $index }}][tanggal_penempatan_baru]"
                                    class="form-control form-control-sm tanggal-penempatan"
                                    value="{{ $item['tanggal_penempatan_baru'] ?? '' }}">
                            </td>
                            <td>
                                <input type="date" name="items[{{ $index }}][tanggal_perpanjangan]"
                                    class="form-control form-control-sm tanggal-perpanjangan"
                                    value="{{ $item['tanggal_perpanjangan'] ?? '' }}">
                            </td>
                            <td>
                                <input type="date" name="items[{{ $index }}][tanggal_jatuh_tempo]"
                                    class="form-control form-control-sm tanggal-jatuh-tempo"
                                    value="{{ $item['tanggal_jatuh_tempo'] ?? '' }}" readonly required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][spesial_nisbah]"
                                    class="form-control form-control-sm" value="{{ $item['spesial_nisbah'] ?? '' }}"
                                    required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][expected_return]"
                                    class="form-control form-control-sm" value="{{ $item['expected_return'] ?? '' }}"
                                    required>
                            </td>
                            <td>
                                <select name="items[{{ $index }}][jenis_transaksi]"
                                    class="form-select form-select-sm" required>
                                    <option value="">Pilih</option>
                                    <option value="Baru"
                                        {{ ($item['jenis_transaksi'] ?? '') == 'Baru' ? 'selected' : '' }}>
                                        Baru
                                    </option>
                                    <option value="Perpanjangan"
                                        {{ ($item['jenis_transaksi'] ?? '') == 'Perpanjangan' ? 'selected' : '' }}>
                                        Perpanjangan
                                    </option>
                                </select>
                            </td>
                            <td class="text-center">
                                @if ($loop->first)
                                    <button type="button" class="btn btn-link text-muted p-0 disabled"><i
                                            class="fas fa-trash"></i></button>
                                @else
                                    <button type="button" class="btn btn-link text-danger p-0"
                                        onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let i = {{ count($items) }};

    function addRow() {
        let html = `<tr>
            <td><input type="text" name="items[${i}][nomor_rekening_deposito]" class="form-control form-control-sm" required></td>
            <td><input type="number" name="items[${i}][nominal]" class="form-control form-control-sm nominal-input" min="0" oninput="calculateTotalNominal()" required></td>
            <td>
                <select name="items[${i}][jangka_waktu]" class="form-select form-select-sm jangka-waktu" required>
                    <option value="">Pilih</option>
                    <option value="1 Bulan">1 Bulan</option>
                    <option value="2 Bulan">2 Bulan</option>
                    <option value="3 Bulan">3 Bulan</option>
                    <option value="4 Bulan">4 Bulan</option>
                    <option value="5 Bulan">5 Bulan</option>
                    <option value="6 Bulan">6 Bulan</option>
                </select>
            </td>
            <td><input type="date" name="items[${i}][tanggal_penempatan_baru]" class="form-control form-control-sm tanggal-penempatan"></td>
            <td><input type="date" name="items[${i}][tanggal_perpanjangan]" class="form-control form-control-sm tanggal-perpanjangan"></td>
            <td> 
                <input type="date" name="items[${i}][tanggal_jatuh_tempo]" class="form-control form-control-sm tanggal-jatuh-tempo" readonly required>
            </td>
            <td><input type="text" name="items[${i}][spesial_nisbah]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="items[${i}][expected_return]" class="form-control form-control-sm" required></td>
            <td>
                <select name="items[${i}][jenis_transaksi]" class="form-select form-select-sm" required>
                    <option value="">Pilih</option>
                    <option value="Baru">Baru</option>
                    <option value="Perpanjangan">Perpanjangan</option>
                </select>
            </td>
            <td class="text-center"><button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>
        </tr>`;
        document.querySelector('#itemsTable tbody').insertAdjacentHTML('beforeend', html);
        i++;
        calculateTotalNominal();
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateTotalNominal();
    }

    function calculateTotalNominal() {
        let total = 0;

        document.querySelectorAll('.nominal-input').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });

        document.getElementById('total_nominal').value = total;
    }

    function generateNomorSurat() {

        const cabang = document.getElementById('cabang').value;

        if (!cabang) {
            document.getElementById('nomor_surat').value = '';
            return;
        }

        let kodeCabang = cabang.split('(')[0].trim().toUpperCase();

        document.getElementById('nomor_surat').value =
            `AUTO/${kodeCabang}/${new Date().getFullYear()}`;
    }

    function hitungJatuhTempo(row) {

        const jangka = row.querySelector('.jangka-waktu').value;

        const penempatan = row.querySelector('.tanggal-penempatan').value;
        const perpanjangan = row.querySelector('.tanggal-perpanjangan').value;

        const jatuhTempo = row.querySelector('.tanggal-jatuh-tempo');

        let tanggalAwal = perpanjangan || penempatan;

        if (!tanggalAwal || !jangka) {
            jatuhTempo.value = '';
            return;
        }

        let bulan = parseInt(jangka);

        let tanggal = new Date(tanggalAwal);

        const hari = tanggal.getDate();

        tanggal.setMonth(tanggal.getMonth() + bulan);

        if (tanggal.getDate() !== hari) {
            tanggal.setDate(0);
        }

        jatuhTempo.value = tanggal.toISOString().split('T')[0];
    }

    document.addEventListener('change', function(e) {

        if (
            e.target.classList.contains('jangka-waktu') ||
            e.target.classList.contains('tanggal-penempatan') ||
            e.target.classList.contains('tanggal-perpanjangan')
        ) {
            hitungJatuhTempo(e.target.closest('tr'));
        }

    });

    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
            hitungJatuhTempo(row);
        });

    });

    document.getElementById('cabang').addEventListener('change', generateNomorSurat);
    document.addEventListener('DOMContentLoaded', generateNomorSurat);

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotalNominal();
    });
</script>

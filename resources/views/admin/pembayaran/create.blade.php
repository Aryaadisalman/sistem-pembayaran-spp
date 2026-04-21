<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="p-0 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Tambah Pembayaran Langsung</h3>
                        <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                    
                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                    @endif
                    
                    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data" id="payment-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Informasi Siswa</h4>
                                
                                    <div class="mb-4">
                                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                                        <select id="siswa_id" name="siswa_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                            <option value="">Pilih Siswa</option>
                                            @foreach($siswa as $s)
                                                <option value="{{ $s->siswa_id }}" {{ old('siswa_id') == $s->siswa_id ? 'selected' : '' }}>
                                                    {{ $s->nis }} - {{ $s->nama }} ({{ $s->kelas }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('siswa_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Pilih Item Pembayaran</h4>
                                    
                                    <div class="mb-4">
                                        <div class="grid grid-cols-1 gap-4 mb-4" id="selected-items">
                                            <div class="flex items-center space-x-2 bg-white dark:bg-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500">
                                                <div class="flex-grow">
                                                    <select name="payment_items[0][spp_id]" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 payment-item-select" required>
                                                        <option value="">Pilih Item</option>
                                                        @foreach($spp as $s)
                                                            <option value="{{ $s->spp_id }}" data-nominal="{{ $s->nominal }}">
                                                                {{ $s->nama }} (Rp {{ number_format($s->nominal, 0, ',', '.') }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="button" class="text-red-500 hover:text-red-700 remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <button type="button" id="add-item" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-2 rounded-md text-sm flex items-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Detail Pembayaran</h4>
                                    
                                    <div class="mb-4">
                                        <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pembayaran</label>
                                        <select id="status_pembayaran" name="status_pembayaran" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                            <option value="belum_bayar" {{ old('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                            <option value="pending" {{ old('status_pembayaran') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="lunas" {{ old('status_pembayaran', 'lunas') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                            <option value="ditolak" {{ old('status_pembayaran') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        @error('status_pembayaran')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Bayar</label>
                                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                        @error('tanggal_bayar')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran</label>
                                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', date('Y').'/'.date('Y')+1) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                        @error('tahun_ajaran')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('keterangan', 'Pembayaran langsung ke bendahara sekolah') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-4 border-b pb-2">Informasi Pembayaran</h4>
                                    
                                    <div class="mb-4">
                                        <label for="total_tagihan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Tagihan (Rp)</label>
                                        <input type="number" name="total_tagihan" id="total_tagihan" readonly class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 bg-gray-100 dark:bg-gray-700" value="{{ old('total_tagihan', 0) }}">
                                        @error('total_tagihan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="total_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Bayar (Rp)</label>
                                        <input type="number" name="total_bayar" id="total_bayar" value="{{ old('total_bayar', 0) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                        @error('total_bayar')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                                        <select id="metode_pembayaran" name="metode_pembayaran" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                            <option value="tunai" {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                        </select>
                                        @error('metode_pembayaran')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="bukti_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Bukti Pembayaran (Opsional)</label>
                                        <input type="file" name="bukti_bayar" id="bukti_bayar" class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                                        @error('bukti_bayar')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded-md flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedItemsContainer = document.getElementById('selected-items');
            const addItemButton = document.getElementById('add-item');
            const totalTagihanInput = document.getElementById('total_tagihan');
            const totalBayarInput = document.getElementById('total_bayar');
            let itemCounter = 1; // Start from 1 since we already have item[0]
            
            // Add new payment item
            addItemButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'flex items-center space-x-2 bg-white dark:bg-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500';
                newItem.innerHTML = `
                    <div class="flex-grow">
                        <select name="payment_items[${itemCounter}][spp_id]" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 payment-item-select" required>
                            <option value="">Pilih Item</option>
                            @foreach($spp as $s)
                                <option value="{{ $s->spp_id }}" data-nominal="{{ $s->nominal }}">
                                    {{ $s->nama }} (Rp {{ number_format($s->nominal, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                
                selectedItemsContainer.appendChild(newItem);
                
                // Add event listener to the new select element
                const newSelect = newItem.querySelector('.payment-item-select');
                newSelect.addEventListener('change', updateTotalTagihan);
                
                // Add event listener to the new remove button
                const removeButton = newItem.querySelector('.remove-item');
                removeButton.addEventListener('click', function() {
                    newItem.remove();
                    updateTotalTagihan();
                });
                
                itemCounter++;
                updateTotalTagihan();
            });
            
            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    // Don't remove if it's the last item
                    if (selectedItemsContainer.children.length > 1) {
                        this.closest('div.flex').remove();
                        updateTotalTagihan();
                    } else {
                        alert('Minimal harus ada satu item pembayaran');
                    }
                });
            });
            
            // Add event listeners to existing select elements
            document.querySelectorAll('.payment-item-select').forEach(select => {
                select.addEventListener('change', updateTotalTagihan);
            });
            
            // Calculate total tagihan based on selected items
            function updateTotalTagihan() {
                let total = 0;
                
                document.querySelectorAll('.payment-item-select').forEach(select => {
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const nominal = parseInt(selectedOption.dataset.nominal || 0);
                        total += nominal;
                    }
                });
                
                totalTagihanInput.value = total;
                totalBayarInput.value = total; // Set total_bayar to match total_tagihan by default
            }
            
            // Update total_bayar when total_tagihan changes
            totalTagihanInput.addEventListener('change', function() {
                if (totalBayarInput.value === '' || parseInt(totalBayarInput.value) === 0) {
                    totalBayarInput.value = this.value;
                }
            });
            
            // Initial calculation
            updateTotalTagihan();
        });
    </script>
    @endpush
</x-app-layout>
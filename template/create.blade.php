<x-app-layout>
    <div class="p-4 sm:ml-[200px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Tambah Data Siswa') }}
                </h2>
            </div>

            <div class="p-0 text-gray-900 dark:text-gray-100">
                <form method="POST" action="{{ route('admin.siswa.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <x-input-label for="nama" :value="__('Nama')" class="dark:text-gray-300" />
                            <x-text-input id="nama" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="nama" :value="old('nama')" required autofocus autocomplete="nama" />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- NIS -->
                        <div>
                            <x-input-label for="nis" :value="__('NIS')" class="dark:text-gray-300" />
                            <x-text-input id="nis" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="nis" :value="old('nis')" required autocomplete="nis" pattern="[0-9]{10}" title="NIS harus berupa 10 digit angka" />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">NIS harus 10 digit angka</p>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <x-input-label for="kelas" :value="__('Kelas')" class="dark:text-gray-300" />
                            <select id="kelas" name="kelas" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih Kelas</option>
                                <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>Kelas X</option>
                                <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                                <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                            </select>
                            <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                            <x-text-input id="email" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="dark:text-gray-300" />
                            <x-text-input id="password" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="dark:text-gray-300" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.siswa.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Kembali
                        </a>
                        <x-primary-button class="ml-4">
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

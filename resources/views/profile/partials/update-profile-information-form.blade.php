<section class="bg-white text-black">
    <header>
        <h2 class="text-lg font-medium text-black">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Show fields based on role --}}
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'kepsek')
            {{-- Admin fields --}}
            <div>
                <x-input-label for="nama" :value="__('Nama')" class="text-black" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('nama', $user->admin->nama ?? $user->name)" required autofocus autocomplete="nama" />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="no_telp" :value="__('Nomor Telepon')" class="text-black" />
                <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('no_telp', $user->admin->no_telp ?? '')" autocomplete="tel" />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('no_telp')" />
            </div>

            <div>
                <x-input-label for="alamat" :value="__('Alamat')" class="text-black" />
                <textarea id="alamat" name="alamat" class="mt-1 block w-full bg-white text-black border-gray-300 rounded-md shadow-sm" rows="3">{{ old('alamat', $user->admin->alamat ?? '') }}</textarea>
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('alamat')" />
            </div>
        @elseif(Auth::user()->role == 'siswa')
            {{-- Siswa fields --}}
            <div>
                <x-input-label for="nama" :value="__('Nama')" class="text-black" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('nama', $user->siswa->nama ?? $user->name)" required autofocus autocomplete="nama" />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="nis" :value="__('NIS')" class="text-black" />
                <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('nis', $user->siswa->nis ?? '')" required />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('nis')" />
            </div>

            <div>
                <x-input-label for="kelas" :value="__('Kelas')" class="text-black" />
                <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('kelas', $user->siswa->kelas ?? '')" required readonly />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('kelas')" />
            </div>

            <div>
                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" class="text-black" />
                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('tanggal_masuk', $user->siswa->tanggal_masuk ?? '')" readonly />
                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('tanggal_masuk')" />
            </div>
        @endif

        {{-- Email field for all users --}}
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-black" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-white text-black border-gray-300" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik disini untuk mengirimkan email verifikasi lagi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-blue-600">
                            {{ __('Email verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-indigo-600 text-white hover:bg-indigo-700">{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

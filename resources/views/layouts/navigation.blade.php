<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sistem Pembayaran SPP</title>
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <style>
      body {
         font-family: 'Inter', sans-serif;
      }
      
      /* Custom Scrollbar */
      ::-webkit-scrollbar {
         width: 6px;
         height: 6px;
      }
      ::-webkit-scrollbar-track {
         background: #f1f1f1;
         border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb {
         background: #c5c5c5;
         border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb:hover {
         background: #34c759;
      }
      
      /* Sidebar animations */
      .sidebar-item {
         transition: all 0.2s ease;
      }
      .sidebar-item:hover {
         transform: translateX(3px);
      }
      
      /* Active menu indicator */
      .active-menu-item {
         position: relative;
         background-color: rgba(43, 202, 38, 0.08);
         color:rgb(43, 202, 38) !important;
      }
      .active-menu-item::before {
         content: '';
         position: absolute;
         left: 0;
         top: 0;
         bottom: 0;
         width: 3px;
         background: linear-gradient(to bottom, #34c759, #4ade80);
         border-radius: 0 3px 3px 0;
      }
   </style>
</head>
<body>
   {{-- Navbar --}}
   <nav class="fixed top-0 z-50 w-full bg-white/80 border-b border-gray-200 backdrop-blur-lg shadow-sm">
      <div class="px-3 py-3 lg:px-5 lg:pl-3">
         <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
               @auth
                  @if (Auth::user()->role == 'admin')
                     <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-full sm:hidden hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                           <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                     </button>
                  @endif
               @endauth
                  <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'kepsek' ? route('admin.dashboard') : route('dashboard')) }}" class="flex items-center ms-2 md:me-24 space-x-3">
                     <div class="relative">
                        <img src="{{ asset('logo/icon.png') }}" class="h-9 w-auto object-contain transition-transform hover:scale-105" alt="Logo" />
                        <div class="absolute inset-0 bg-blue-500 rounded-full filter blur-md opacity-10 -z-10 animate-pulse"></div>
                     </div>
                     <span class="font-semibold text-gray-800 hidden md:block">Sistem Pembayaran SPP</span>
                  </a>
            </div>
            <div class="flex items-center">
               <div class="flex items-center ms-3">
                  <div>
                     <button type="button" class="flex text-sm bg-gradient-to-r from-blue-100 to-blue-50 p-0.5 rounded-full focus:ring-4 focus:ring-blue-300 shadow-sm hover:shadow transition-all duration-200" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full p-0.5 border border-white/40"
                     src="https://ui-avatars.com/api/?name={{ Auth::user()->email }}&background=random"
                     alt="user photo">
                     </button>
                  </div>
                  <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-md shadow-lg" id="dropdown-user">
                     <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-t-md" role="none">
                        <p class="text-sm font-medium text-gray-800" role="none">
                           {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-600 truncate" role="none">
                           {{ Auth::user()->email }}
                        </p>
                     </div>
                     <ul class="py-1" role="none">
                        <li><a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-500">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                           </svg>
                           Profile
                        </a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                Sign out
                            </button>
                        </form>
                    </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </nav>
   {{-- Navbar --}}
   
  
   @auth
      
   @if (Auth::user()->role == 'admin')

       {{-- Sidebar --}}
     <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 shadow-sm" aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
         <div class="mx-3 mb-5">
            <div class="relative p-3 mt-2 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50">
               <div class="text-sm font-medium text-gray-600">Welcome,</div>
               <div class="font-semibold text-gray-800 truncate">{{ Auth::user()->admin ? Auth::user()->admin->nama : Auth::user()->name }}</div>
               <div class="absolute top-0 right-0 p-1">
                  <div class="h-2 w-2 rounded-full bg-blue-400 shadow-sm animate-pulse"></div>
               </div>
            </div>
         </div>
         <ul class="space-y-1 font-medium">
            <li>
               <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'kepsek' ? route('admin.dashboard') : route('dashboard')) }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.dashboard') ? 'active-menu-item' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.pembayaran.index') }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.pembayaran.*') ? 'active-menu-item' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.pembayaran.*') ? 'text-blue-500' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Pembayaran</span>
                  @php
                     $pendingCount = \App\Models\Pembayaran::where('status_pembayaran', 'pending')->count();
                  @endphp
                  @if($pendingCount > 0)
                     <span class="inline-flex items-center justify-center h-5 ml-auto px-2 text-xs font-semibold text-white bg-blue-500 rounded-full">{{ $pendingCount }}</span>
                  @endif
               </a>
            </li>

            <li x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
               <a href="#" @click.prevent="open = !open" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.users.*') ? 'text-blue-500' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Akun</span>
                  <svg class="ml-auto size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </a>
               <ul x-show="open" x-collapse class="mt-1 pl-[2.5rem] border-l border-gray-100 ml-2 space-y-1">
                     <li>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.users.index') ? 'text-blue-600' : '' }}">
                           <span class="font-medium truncate">Semua Akun</span>
                        </a>
                     </li>
                     <li>
                        <a href="{{ route('admin.users.create') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.users.create') ? 'text-blue-600' : '' }}">
                           <span class="font-medium truncate">Tambah Akun</span>
                        </a>
                     </li>
               </ul> 
            </li>

            <li class="mt-6 mb-2">
               <div class="mx-4">
                  <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
               </div>
               <span class="flex items-center p-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Data Master</span>
            </li>
            <li>
               <a href="{{ route('admin.spp.index') }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.spp.*') ? 'active-menu-item' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.spp.*') ? 'text-blue-500' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm0 5h6m-6 4h6m-6 4h6"/>
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">SPP & PPDB</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.siswa.index') }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.siswa.*') ? 'active-menu-item' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.siswa.*') ? 'text-blue-500' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18v-6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12v6m4.5-9v5.25m0 0h5.25M7.5 15h3m-3 3h12" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Data Siswa</span>
               </a>
            </li>

            <li x-data="{ open: false }">
               <a href="#" @click.prevent="open = !open" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Laporan</span>
                  <svg class="ml-auto size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </a>
               <ul x-show="open" x-collapse class="mt-1 pl-[2.5rem] border-l border-gray-100 ml-2 space-y-1">
                    <li>
                      <a href="{{ route('admin.laporan.pembayaran') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Pembayaran</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.laporan.siswa-lunas') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Siswa Lunas</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.laporan.siswa-menunggak') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Siswa Menunggak</span>
                      </a>
                      <a href="{{ route('admin.laporan.harian') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Laporan Harian</span>
                      </a>
                    </li>
               </ul>
            </li>
            
         </ul>
      </div>
   </aside> 
   
   @elseif (Auth::user()->role == 'kepsek')
    
       {{-- Sidebar --}}
     <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 shadow-sm" aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
         <div class="mx-3 mb-5">
            <div class="relative p-3 mt-2 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50">
               <div class="text-sm font-medium text-gray-600">Welcome,</div>
               <div class="font-semibold text-gray-800 truncate">{{ Auth::user()->admin ? Auth::user()->admin->nama : Auth::user()->name }}</div>
               <div class="absolute top-0 right-0 p-1">
                  <div class="h-2 w-2 rounded-full bg-blue-400 shadow-sm animate-pulse"></div>
               </div>
            </div>
         </div>
         <ul class="space-y-1 font-medium">
            <li>
                  <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'kepsek' ? route('admin.dashboard') : route('dashboard')) }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.dashboard') ? 'active-menu-item' : '' }}">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Dashboard</span>
               </a>
            </li>
            <li x-data="{ open: false }">
               <a href="#" @click.prevent="open = !open" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                  <div class="flex items-center justify-center min-w-[2rem]">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-400 group-hover:text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                     </svg>
                  </div>
                  <span class="ml-3 font-medium text-sm">Laporan</span>
                  <svg class="ml-auto size-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
               </a>
               <ul x-show="open" x-collapse class="mt-1 pl-[2.5rem] border-l border-gray-100 ml-2 space-y-1">
                    <li>
                      <a href="{{ route('admin.laporan.pembayaran') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Pembayaran</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.laporan.siswa-lunas') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Siswa Lunas</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('admin.laporan.siswa-menunggak') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Siswa Menunggak</span>
                      </a>
                      <a href="{{ route('admin.laporan.harian') }}" class="sidebar-item flex items-center py-2 pl-1 pr-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group">
                        <span class="font-medium truncate">Laporan Harian</span>
                      </a>
                    </li>
               </ul>
            </li>
 
         </ul>
      </div>
   </aside> 
   @else
      {{-- Buttom-bar --}}
   <div class="fixed bottom-0 left-0 right-0 bg-white/80 border-t border-gray-200 backdrop-blur-lg shadow-sm z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-around h-16 items-center">
                    <!-- Dashboard -->
                    <a class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 transition-colors duration-200 relative group"
                        href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}">
                            <div class="p-1.5 rounded-full group-hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-home text-xl"></i>
                            </div>
                            <span class="text-xs font-medium mt-1">Dashboard</span>
                            <div class="absolute bottom-0 h-0.5 w-0 bg-blue-500 group-hover:w-full transition-all duration-300"></div>
                        </a>

                    <!-- Pembayaran -->
                    <a class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 transition-colors duration-200 relative group"
                        href="{{ route('pembayaran.create') }}">
                        <div class="p-1.5 rounded-full group-hover:bg-blue-50 transition-colors duration-200">
                            <i class="fa fa-credit-card text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Pembayaran</span>
                        <div class="absolute bottom-0 h-0.5 w-0 bg-blue-500 group-hover:w-full transition-all duration-300"></div>
                    </a>

                    <!-- Riwayat -->
                    <a class="flex flex-col items-center justify-center text-gray-500 hover:text-blue-600 transition-colors duration-200 relative group"
                        href="{{ route('pembayaran.history') }}">
                        <div class="p-1.5 rounded-full group-hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-history text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Riwayat</span>
                        <div class="absolute bottom-0 h-0.5 w-0 bg-blue-500 group-hover:w-full transition-all duration-300"></div>
                    </a>

                    <!-- Profile -->
                    <div class="relative flex flex-col items-center justify-center group">
                        <button onclick="toggleProfileMenu()"
                            class="flex flex-col items-center text-gray-500 hover:text-blue-600 transition-colors duration-200 focus:outline-none">
                            <div class="p-1.5 rounded-full group-hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <span class="text-xs font-medium mt-1">Profile</span>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profileMenu"
                            class="hidden absolute -top-28 bg-white border border-gray-200 rounded-lg shadow-lg p-1 w-36 z-50">
                            <a class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md transition-colors"
                                href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog text-blue-500 text-sm"></i>
                                Profile
                            </a>
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                                    <i class="fas fa-sign-out-alt text-red-500 text-sm"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                        <div class="absolute bottom-0 h-0.5 w-0 bg-blue-500 group-hover:w-full transition-all duration-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
    // Toggle profile menu with animation
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.classList.add('animate-fadeIn');
        } else {
            menu.classList.add('animate-fadeOut');
            setTimeout(() => {
                menu.classList.add('hidden');
                menu.classList.remove('animate-fadeOut');
            }, 200);
        }
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profileMenu');
        const menuButton = document.querySelector('button[onclick="toggleProfileMenu()"]');
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !menuButton.contains(event.target)) {
            toggleProfileMenu();
        }
    });
</script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(10px); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out forwards;
    }
    .animate-fadeOut {
        animation: fadeOut 0.2s ease-in forwards;
    }
</style>
@endif
@endauth
</body>
</html>
                <!-- Angsuran DU -->
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.angsuran.du.index') }}" class="sidebar-item flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 group {{ request()->routeIs('admin.angsuran.du.*') ? 'active-menu-item' : '' }}">
                   <svg class="size-5 text-gray-400 group-hover:text-blue-500 {{ request()->routeIs('admin.angsuran.du.*') ? 'text-blue-500' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10H5m14 0h-5M9 14H5m14 0h-5M4 4h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/>
                   </svg>
                   <span class="ml-3 font-medium text-sm">Angsuran DU</span>
                   @php $pendingDu = \App\Models\AngsuranDu::where('status', 'pending')->count(); @endphp
                   @if($pendingDu > 0)
                   <span class="ml-auto bg-orange-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $pendingDu }}</span>
                   @endif
                </a>
                @endif
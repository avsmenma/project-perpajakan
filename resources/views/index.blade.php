<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pencarian Data Karyawan - NPWP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #4f46e5 100%);
        }

        .search-input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.3);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>

<body class="gradient-bg min-h-screen font-inter">
    <!-- Top Navigation Bar -->
    @if(Session::has('user'))
        <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-lg border-b border-slate-700/50">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ Session::get('user.nama') }}</p>
                            <p class="text-slate-400 text-sm">{{ Session::get('user.nik_sap') }} •
                                {{ Session::get('user.nama_kebun') }}
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 rounded-xl transition-all duration-300 border border-red-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endif

    <div
        class="min-h-screen flex flex-col items-center justify-center px-4 py-8 {{ Session::has('user') ? 'pt-24' : '' }}">
        <!-- Success Message -->
        @if(session('success'))
            <div class="w-full max-w-2xl mb-6 fade-in">
                <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Pencarian Data Karyawan</h1>
            <p class="text-slate-300 text-lg">Masukkan NPWP untuk mencari informasi karyawan</p>
        </div>


        <!-- Search Card -->
        <div class="w-full max-w-2xl glass-card rounded-3xl shadow-2xl p-6 md:p-8 fade-in">
            <form action="{{ route('search') }}" method="GET" class="space-y-4">
                @csrf
                <div class="relative">
                    <label for="npwp" class="block text-sm font-semibold text-slate-700 mb-2">
                        Nomor Pokok Wajib Pajak (NPWP)
                    </label>
                    <div class="relative">
                        <input type="text" id="npwp" name="npwp" value="{{ $npwp ?? '' }}"
                            placeholder="Contoh: 6193020504020001"
                            class="search-input w-full px-5 py-4 text-lg border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:outline-none transition-all duration-300 bg-white/80"
                            autocomplete="off">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Masukkan 15-16 digit angka</p>
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari Data Karyawan
                </button>
            </form>
        </div>

        <!-- Error Message -->
        @if(isset($error))
            <div class="w-full max-w-2xl mt-6 fade-in">
                <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-red-700 font-medium">{{ $error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Result Card -->
        @if(isset($result) && $result)
            <div class="w-full max-w-6xl mt-6 fade-in">
                <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Data Ditemukan</h2>
                                <p class="text-emerald-100 text-sm">Informasi karyawan berhasil ditemukan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Content - Horizontal Table -->
                    <div class="p-6 overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b-2 border-slate-200">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50 rounded-tl-lg">
                                        Nama
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                        NPWP
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                        NIK SAP
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                        Nama Kebun
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50">
                                        Bupot A1
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50 rounded-tr-lg">
                                        Link Download
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-slate-800">{{ $result->nama }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-800 font-mono text-sm font-medium">
                                            {{ $result->npwp }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm font-medium text-slate-700">{{ $result->nik_sap ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-sm font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $result->nama_kebun ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 font-mono text-sm font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            {{ $result->bupot_a1 ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if($result->link_pdf)
                                            <a href="{{ route('download.pdf', ['path' => $result->link_pdf]) }}"
                                                class="inline-flex items-center px-3 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                Download PDF
                                            </a>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-sm">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                    </path>
                                                </svg>
                                                Tidak tersedia
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="bg-slate-50 px-6 py-4">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cari NPWP Lainnya
                        </a>
                    </div>
                </div>
            </div>
        @endif



        <!-- Footer -->
        <footer class="mt-8 text-center text-slate-400 text-sm">
            <p>&copy; {{ date('Y') }} Sistem Pencarian Data Karyawan. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
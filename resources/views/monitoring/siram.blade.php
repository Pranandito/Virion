<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>{{ $device->name }}</title>
    <link rel="shortcut icon" href="{{ asset('images/Logo.png') }}" type="image/x-icon">

    <style>
        .activity-log-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .activity-log-scroll::-webkit-scrollbar-thumb {
            background: #E0E0E0;
            border-radius: 9999px;
        }

        .activity-log-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    <style>
        #chartTooltip {
            opacity: 0;
            position: absolute;
            background: #E2E7E3;
            border-radius: 12px;
            padding: 14px 16px;
            pointer-events: none;
            transition: opacity 0.15s ease;
            font-family: inherit;
            color: #4D5650;
            min-width: 160px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            z-index: 10;
        }

        #chartTooltip .tt-date {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
        }

        #chartTooltip .tt-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #chartTooltip .tt-bar {
            width: 3px;
            height: 32px;
            border-radius: 2px;
            background: #03D076;
        }

        #chartTooltip .tt-label {
            font-size: 11px;
            color: #79857E;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        #chartTooltip .tt-value {
            font-size: 18px;
            font-weight: 700;
        }
    </style>
</head>

<x-beranda.side-bar :devices="$devices" />

<!-- form update treshold kelembapan -->
<aside id="threshold-form" class="hidden">
    <div
        class="fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                <img src="setting.svg" alt="">
                <h1 class="text-lg lg:text-xl">Pengaturan treshold kelembapan</h1>
            </div>
            <button id="form-exit" type="button" class="px-2 py-1 rounded-full hover:bg-[#D1D1C6] cursor-pointer">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p>Atur batas atas dan batas bawah kelembapan lahan pertanian anda</p>
        <form method="POST" action="{{ route('edit-threshold') }}" class="mt-4">
            @csrf
            <div class="lg:flex justify-between">
                <div>
                    <!-- Batas Bawah (Lower Threshold) -->
                    <label for="up-threshold" class="ms-1">Batas bawah:</label>
                    <div class="bg-white border border-gray-200 rounded-lg w-60 mt-1 mb-3" data-hs-input-number="">
                        <div class="w-full flex justify-between items-center gap-x-1">
                            <div class="grow py-2 px-3 flex">
                                <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0"
                                    type="number"
                                    name="lower_threshold"
                                    aria-roledescription="Number field"
                                    step="0.01"
                                    min="0"
                                    max="99.99"
                                    value="{{ $device->$config_table->lower_threshold, 1 }}"
                                    data-hs-input-number-input=""
                                    id="up-threshold">
                                <span class="text-gray-300">%</span>
                            </div>
                            <div class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200">
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="up-thres-decrease">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="up-thres-increase">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Batas Atas (Upper Threshold) -->
                    <label for="up-threshold" class="ms-1">Batas atas:</label>
                    <div class="bg-white border border-gray-200 rounded-lg mt-1 w-60" data-hs-input-number="">
                        <div class="w-full flex justify-between items-center gap-x-1">
                            <div class="grow py-2 px-3 flex">
                                <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0"
                                    type="number"
                                    name="upper_threshold"
                                    aria-roledescription="Number field"
                                    step="0.01"
                                    min="0"
                                    max="99.99"
                                    value="{{ $device->$config_table->upper_threshold, 1 }}"
                                    data-hs-input-number-input=""
                                    id="low-threshold">
                                <span class="text-gray-300">%</span>
                            </div>
                            <div class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200">
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="low-thres-decrease">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="low-thres-increase">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="virdi_type" value="{{ $device->virdi_type }}">
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="hidden" name="serial_number" value="{{ $device->serial_number }}">
            <input type="hidden" name="name" value="{{ $device->name }}">

            <button type="submit" id="submit-form-humidity" class="w-full lg:mt-1 mt-3">
                <h1 class="cursor-pointer border rounded-xl border-[#80B56F] text-base px-5 py-1 hover:text-[#FFFFF0] hover:bg-[#80B56F] flex justify-center">
                    Simpan
                </h1>
            </button>
        </form>
    </div>
</aside>

<aside id="schedule-form" class="hidden">
    <div
        class="fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11 text-base">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center text-gray-800 gap-5 text-xl mb-2">
                <svg width="18" height="18" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_899_2)">
                        <path d="M10.5 6H6M6 6H1.5M6 6V1.5M6 6V10.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_899_2">
                            <rect width="12" height="12" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <h1 class="text-lg lg:text-xl">Tambah Jadwal Baru</h1>
            </div>
            <button id="form-schedule-exit" type="button" class="px-2 py-1 rounded-full hover:bg-[#D1D1C6] cursor-pointer">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <hr class="border-[#b7b7b7] my-4">
        <p class="text-[#979797] text-sm hidden lg:block">Tambahkan jadwal penyiraman baru dengan mengatur waktu dan durasi penyiraman sesuai kebutuhan tanaman secara otomatis.</p>

        <form id="form-jadwal" action="{{ route('siram.add-schedule', ['device_id' => $device->id]) }}" method="POST">
            @csrf

            <!-- Durasi & Waktu Penyiraman (sejajar 2 kolom) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="input-duration" class="block mb-2 text-base text-gray-900">Durasi Penyiraman :</label>
                    <div class="bg-white border border-gray-200 rounded-lg w-full" data-hs-input-number="">
                        <div class="w-full flex justify-between items-center gap-x-1">
                            <div class="grow py-2 px-3 flex">
                                <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0" type="text"
                                    inputmode="decimal" name="duration" aria-roledescription="Number field"
                                    autocomplete="off" value="1,0"
                                    data-hs-input-number-input="" id="input-duration">
                                <span class="text-gray-300 whitespace-nowrap">Menit</span>
                            </div>
                            <div class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200">
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="decrease-duration">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                    id="increase-duration">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="time" class="block mb-2 text-base text-gray-900">Waktu Penyiraman :</label>
                    <div class="relative bg-white border border-gray-200 rounded-lg w-full">
                        <div class="flex items-center justify-between px-3 py-2">
                            <input
                                type="time"
                                id="time"
                                name="time"
                                class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0 focus:outline-none leading-none"
                                min="00:00"
                                max="24:00"
                                value="12:00"
                                required />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilihan Hari (pill / badge, satu baris) -->
            <label class="block mb-2 mt-5 text-base text-gray-900">Pilih hari penjadwalan pemberian pakan:</label>
            <div class="flex flex-wrap gap-2 justify-center">
                @php
                $hari = [
                'senin' => 'Sen',
                'selasa' => 'Sel',
                'rabu' => 'Rab',
                'kamis' => 'Kam',
                'jumat' => 'Jum',
                'sabtu' => 'Sab',
                'minggu' => 'Min',
                ];
                @endphp

                @foreach ($hari as $value => $label)
                <label for="{{ $value }}" class="cursor-pointer">
                    <input
                        name="days[]"
                        id="{{ $value }}"
                        type="checkbox"
                        value="{{ $value }}"
                        class="peer hidden">
                    <span
                        class="inline-block px-4 py-1.5 text-sm rounded-full border border-gray-300 text-gray-700 bg-white
                                   peer-checked:bg-[#80B56F] peer-checked:text-[#FFFFF0] peer-checked:border-[#80B56F]
                                   hover:bg-[#80B56F] hover:text-[#FFFFF0] transition-colors">
                        {{ $label }}
                    </span>
                </label>
                @endforeach
            </div>

            <input type="hidden" name="name" value="{{ $device->name }}">

            <button id="btn-submit-tambah-jadwal" type="submit"
                class="cursor-pointer mt-6 w-full border border-[#80B56F] hover:bg-[#80B56F] text-gray-900 hover:text-[#FFFFF0] rounded-full transition-colors">
                <h1 class="py-1.5 text-[#80B56F] hover:text-[#FFFFF0]">Tambahkan</h1>
            </button>
        </form>
    </div>
</aside>

<aside id="overlay" class="hidden fixed top-0 right-0 left-0 bottom-0 z-10 bg-gray-600 opacity-40 cursor-pointer">
</aside>

<div id="schedule-detail-modal" class="hidden fixed inset-0 z-20 flex items-center justify-center p-4">
    <div class="relative bg-[#FFFFF0] rounded-[20px] p-8 w-full max-w-md max-h-[85vh] overflow-y-auto shadow-xl scrollbar-hide">
        <div class="flex items-center justify-between mb-6 text-gray-800">
            <div class="flex items-center gap-3 text-lg">
                <div class="p-3 bg-[#80B56F] rounded-full">
                    <svg width="22" height="22" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.6666 2.33337V7.00004M9.33325 2.33337V7.00004" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15.1667 4.66663H12.8333C8.43355 4.66663 6.23367 4.66663 4.86683 6.03346C3.5 7.4003 3.5 9.60018 3.5 14V16.3333C3.5 20.733 3.5 22.933 4.86683 24.2998C6.23367 25.6666 8.43355 25.6666 12.8333 25.6666H15.1667C19.5664 25.6666 21.7664 25.6666 23.1331 24.2998C24.5 22.933 24.5 20.733 24.5 16.3333V14C24.5 9.60018 24.5 7.4003 23.1331 6.03346C21.7664 4.66663 19.5664 4.66663 15.1667 4.66663Z" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3.5 11.6666H24.5" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13.9947 16.3334H14.0052M13.9947 21H14.0052M18.6561 16.3334H18.6666M9.33325 16.3334H9.34372M9.33325 21H9.34372" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h1 class="text-base lg:text-lg font-medium">Detail Jadwal Penyiraman</h1>
            </div>
            <button type="button" id="schedule-detail-close" class="text-gray-500 px-2 py-1 rounded-full hover:bg-[#979797]/50">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <div class="flex flex-col gap-3">
            @forelse($device->siram_schedules as $schedule)
            @php
            $dayNames = [
            'senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu',
            'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu',
            ];
            $isEveryday = $schedule->day_count >= 7;
            @endphp
            <div class="bg-white/60 rounded-2xl p-4 border border-[#E5E5DC]">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 text-gray-800">
                        <i class="bi bi-clock text-[#80B56F]"></i>
                        <span class="font-semibold text-base">{{ date('H:i', strtotime($schedule->time)) }}</span>
                    </div>
                    <span class="text-sm text-gray-600">
                        {{ number_format($schedule->duration / 60, 1) }} menit
                    </span>
                </div>

                <div class="flex flex-wrap justify-between items-center">
                    <div class="flex flex-wrap gap-1.5 w-68">
                        @if($isEveryday)
                        <span class="text-xs bg-[#80B56F] text-white px-2.5 py-1 rounded-full">Setiap Hari</span>
                        @else
                        @php
                        $scheduleDays = is_array($schedule->days) ? $schedule->days : explode(',', $schedule->days ?? '');
                        @endphp

                        @foreach($scheduleDays as $day)
                        <span class="text-xs bg-[#80B56F]/15 text-[#5C8A4F] px-2.5 py-1 rounded-full">
                            {{ $dayNames[$day] ?? ucfirst($day) }}
                        </span>
                        @endforeach @endif
                    </div>
                    <form action="{{ route('siram.delete-schedule', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="ml-5 active:scale-[0.95] p-2 rounded-full hover:bg-[#D9534F]/20 hover:underline cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#D9534F" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm text-center py-6">Belum ada jadwal penyiraman.</p>
            @endforelse
        </div>
    </div>
</div>

<body class="bg-[#F4F7F3]">
    <div class="mx-8 lg:mx-20 pt-8 text-2xl mb-10">
        <nav class="flex justify-between items-center mb-11">
            <div class="flex  items-center lg:gap-11 gap-5">
                <button type="button" id="hamburger" class="cursor-pointer hover:bg-[#D1D1C6] rounded-full px-2 py-1">
                    <i class="bi bi-list text-3xl"></i>
                </button>
                <div class="flex  items-center gap-4">
                    <img src="{{ asset('images//Logo.png') }}" alt="" class="size-14 hidden lg:block">
                    <h1 class="hidden lg:block">Virion&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Dashboard
                        <span>{{ $device->name }}</span>
                    </h1>
                    <h1 class="lg:hidden">Dashboard <span>{{ $device->name }}</span></h1>
                </div>
            </div>
            <div class="hidden lg:block">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center text-xl gap-4 px-8 py-3 rounded-full bg-[#D1D1C6] hover:bg-[#b8b8ae]">
                    <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-full object-cover">
                        <path d="M4.49993 11.25L3.70455 12.0453L2.90918 11.25L3.70455 10.4546L4.49993 11.25ZM23.6249 20.25C23.6249 20.5483 23.5064 20.8345 23.2954 21.0455C23.0844 21.2564 22.7983 21.375 22.4999 21.375C22.2016 21.375 21.9154 21.2564 21.7044 21.0455C21.4935 20.8345 21.3749 20.5483 21.3749 20.25H23.6249ZM9.32956 17.6703L3.70455 12.0453L5.2953 10.4546L10.9203 16.0796L9.32956 17.6703ZM3.70455 10.4546L9.32956 4.82959L10.9203 6.42034L5.2953 12.0453L3.70455 10.4546ZM4.49993 10.125H15.7499V12.375H4.49993V10.125ZM23.6249 18V20.25H21.3749V18H23.6249ZM15.7499 10.125C17.8385 10.125 19.8415 10.9547 21.3184 12.4315C22.7952 13.9083 23.6249 15.9114 23.6249 18H21.3749C21.3749 16.5081 20.7823 15.0774 19.7274 14.0225C18.6725 12.9676 17.2418 12.375 15.7499 12.375V10.125Z" fill="black" />
                    </svg>
                    <h1>
                        Kembali
                    </h1>
                </a>
            </div>
        </nav>

        <main>
            <section class="text-sm text-[#979797]">
                <div class="grid grid-cols-1 lg:grid-cols-3 lg:grid-rows-2 gap-9 ">
                    <div class="row-span-2 bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="block lg:flex items-center justify-between mb-6 text-gray-800">
                            <div class="flex items-center gap-4 text-xl">
                                <div class="p-3 bg-[#80B56F] rounded-full">
                                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5835 16.4577C4.5835 11.5764 8.76016 6.7522 11.6932 3.96737C12.4464 3.23971 13.4528 2.83301 14.5002 2.83301C15.5475 2.83301 16.5539 3.23971 17.3072 3.96737C20.239 6.75337 24.4168 11.5764 24.4168 16.4577C24.4168 21.2434 20.6613 26.1667 14.5002 26.1667C8.339 26.1667 4.5835 21.2434 4.5835 16.4577Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.16699 14.8314C6.87616 14.3017 10.291 14.1314 14.4817 16.4857C18.6653 18.8354 22.1023 17.9977 23.8337 16.9909" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h1>Indeks Kualitas <br> Lahan Greenhouse</h1>
                            </div>
                            <h1 class="text-4xl mt-4 lg:mt-0 w-fit mx-auto lg:mx-0">{{ $index['current'] ?? '-' }}</h1>
                        </div>
                        <p>{{ $index['insight'] ?? '-' }}</p>
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <h1>Rata-rata hari ini</h1>
                            <h1>{{ $index['daily'] ?? '-' }}</h1>
                        </div>
                        <div class="flex justify-between">
                            <h1>Rata-rata minggu ini</h1>
                            <h1>{{ $index['weekly'] ?? '-' }}</h1>
                        </div>
                        <hr class="my-4">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h1 class="text-gray-800 text-base">Mode Penyiraman</h1>
                                <p class="hidden lg:block">Atur mode perangkat anda</p>
                            </div>
                            <x-monitoring.mode-select :mode="$device->siram_config->mode" :name="$device->name" :id="$device->id" :virdi_type="$device->virdi_type" color="border-[#80B56F] hover:bg-[#80B56F]" />
                        </div>
                        <h1 class="text-lg">Tips</h1>
                        <ul class="list-disc list-inside pl-2">
                            <li>
                                Suhu ideal: 25–28°C | Kelembapan ideal: 50–70%
                            </li>
                            <li>
                                Aktifkan mode otomatis untuk menjaga kelembapan dalam batas ideal secara real-time
                            </li>
                        </ul>
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="block lg:flex items-center justify-between mb-6 text-gray-800">
                            <div class="flex items-center gap-4 text-xl">
                                <div class="p-3 bg-[#80B56F] rounded-full">
                                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.5835 16.4577C4.5835 11.5764 8.76016 6.7522 11.6932 3.96737C12.4464 3.23971 13.4528 2.83301 14.5002 2.83301C15.5475 2.83301 16.5539 3.23971 17.3072 3.96737C20.239 6.75337 24.4168 11.5764 24.4168 16.4577C24.4168 21.2434 20.6613 26.1667 14.5002 26.1667C8.339 26.1667 4.5835 21.2434 4.5835 16.4577Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.16699 14.8314C6.87616 14.3017 10.291 14.1314 14.4817 16.4857C18.6653 18.8354 22.1023 17.9977 23.8337 16.9909" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h1>Kelembapan <br> Tanah</h1>
                            </div>
                            <h1 id="humidity" class="text-4xl mx-auto w-fit mt-4 lg:mt-0 lg:mx-0">{{ $latest->humidity ?? '-' }}%</h1>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <h1>Rata-rata hari ini</h1>
                            <h1>{{ round($daily->avg_daily_humidity ?? 0, 2) }}%</h1>
                        </div>
                        <div class="flex justify-between">
                            <h1>Rata-rata minggu ini</h1>
                            <h1>{{ round($weekly->avg_weekly_humidity ?? 0, 2) }}%</h1>
                        </div>
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="flex items-center justify-between mb-6 text-gray-800">
                            <div class="flex items-center gap-4 text-xl">
                                <div class="p-3 bg-[#80B56F] rounded-full">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.6666 2.33337V7.00004M9.33325 2.33337V7.00004" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15.1667 4.66663H12.8333C8.43355 4.66663 6.23367 4.66663 4.86683 6.03346C3.5 7.4003 3.5 9.60018 3.5 14V16.3333C3.5 20.733 3.5 22.933 4.86683 24.2998C6.23367 25.6666 8.43355 25.6666 12.8333 25.6666H15.1667C19.5664 25.6666 21.7664 25.6666 23.1331 24.2998C24.5 22.933 24.5 20.733 24.5 16.3333V14C24.5 9.60018 24.5 7.4003 23.1331 6.03346C21.7664 4.66663 19.5664 4.66663 15.1667 4.66663Z" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M3.5 11.6666H24.5" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.9947 16.3334H14.0052M13.9947 21H14.0052M18.6561 16.3334H18.6666M9.33325 16.3334H9.34372M9.33325 21H9.34372" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-base lg:text-xl">Jadwal Penyiraman</h1>
                                    <button type="button" id="schedule-detail-open" class="text-sm lg:text-base text-[#979797] hover:underline cursor-pointer">
                                        lebih lengkap →
                                    </button>
                                </div>
                            </div>
                            <div>
                                <button id="form-schedule-open" type="button" class="">
                                    <i class="bi bi-three-dots-vertical cursor-pointer text-xl px-2 py-1 rounded-full hover:bg-[#80B56F] hover:text-[#FFFFF0]"></i>
                                </button>
                            </div>
                        </div>
                        <hr class="my-4">
                        @foreach($device->siram_schedules->take(2) as $schedule)
                        <div class="flex justify-between">
                            <div class="flex gap-2">
                                <h1>{{$schedule->day_count < 7 ? 'Harian' : 'Setiap Hari'}}</h1>
                                <h1>-</h1>
                                <h1>{{round($schedule->duration/60, 1)}} menit</h1>
                            </div>
                            <h1>{{ date('H:i', strtotime($schedule->time)) }}</h1>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.3646 22.9167L15.7812 19.5833C16.0069 19.4965 16.2194 19.3924 16.4187 19.2708C16.6181 19.1493 16.8135 19.0191 17.0052 18.8802L20.1042 20.1823L22.9688 15.2344L20.2865 13.2031C20.3038 13.0816 20.3125 12.9646 20.3125 12.8521V12.149C20.3125 12.0358 20.3038 11.9184 20.2865 11.7969L22.9688 9.76562L20.1042 4.81771L17.0052 6.11979C16.8142 5.9809 16.6146 5.85069 16.4062 5.72917C16.1979 5.60764 15.9896 5.50347 15.7812 5.41667L15.3646 2.08333H9.63542L9.21875 5.41667C8.99305 5.50347 8.78021 5.60764 8.58021 5.72917C8.38021 5.85069 8.18507 5.9809 7.99479 6.11979L4.89583 4.81771L2.03125 9.76562L4.71354 11.7969C4.69618 11.9184 4.6875 12.0358 4.6875 12.149V12.851C4.6875 12.9642 4.70486 13.0816 4.73958 13.2031L2.05729 15.2344L4.92188 20.1823L7.99479 18.8802C8.18576 19.0191 8.38542 19.1493 8.59375 19.2708C8.80208 19.3924 9.01042 19.4965 9.21875 19.5833L9.63542 22.9167H15.3646ZM12.4479 16.1458C11.441 16.1458 10.5816 15.7899 9.86979 15.0781C9.15799 14.3663 8.80208 13.5069 8.80208 12.5C8.80208 11.4931 9.15799 10.6337 9.86979 9.92187C10.5816 9.21007 11.441 8.85417 12.4479 8.85417C13.4722 8.85417 14.3361 9.21007 15.0396 9.92187C15.7431 10.6337 16.0944 11.4931 16.0937 12.5C16.0931 13.5069 15.7413 14.3663 15.0385 15.0781C14.3358 15.7899 13.4722 16.1458 12.4479 16.1458Z" fill="#1C1C1C" />
                            </svg>
                            <h1 class="text-lg lg:text-xl">Pengaturan treshold kelembapan</h1>
                        </div>
                        <p>Atur batas atas dan batas bawah kelembapan lahan pertanian anda</p>
                        <div class="block lg:flex items-center justify-between text-4xl text-gray-800 mt-7">
                            <div class="text-3xl flex justify-center">
                                <span>{{ round($device->$config_table->lower_threshold, 1) }}</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span>{{ round($device->$config_table->upper_threshold, 1) }} %</span>
                            </div>
                            <button id="change-threshold" type="button" class="w-full lg:w-30">
                                <h1
                                    class="cursor-pointer border rounded-xl border-[#80B56F] text-base px-5 py-1 hover:text-[#FFFFF0] hover:bg-[#80B56F] flex justify-center mt-4 lg:mt-0">
                                    Ubah</h1>
                            </button>
                        </div>
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="block lg:flex  items-center justify-between mb-6 text-gray-800">
                            <div class="flex items-center gap-4 text-xl">
                                <svg width="53" height="53" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-3 bg-[#80B56F] rounded-full">
                                    <g clip-path="url(#clip0_456_344)">
                                        <path d="M14.5 25C13.6833 25 12.9931 24.718 12.4292 24.1541C11.8653 23.5902 11.5833 22.9 11.5833 22.0833C11.5833 21.2666 11.8653 20.5764 12.4292 20.0125C12.9931 19.4486 13.6833 19.1666 14.5 19.1666C15.3167 19.1666 16.0069 19.4486 16.5708 20.0125C17.1347 20.5764 17.4167 21.2666 17.4167 22.0833C17.4167 22.9 17.1347 23.5902 16.5708 24.1541C16.0069 24.718 15.3167 25 14.5 25ZM7.90833 18.4083L5.45833 15.9C6.60556 14.7527 7.95228 13.8439 9.4985 13.1735C11.0447 12.503 12.7119 12.1674 14.5 12.1666C16.2881 12.1658 17.9557 12.5061 19.5027 13.1875C21.0497 13.8688 22.396 14.7924 23.5417 15.9583L21.0917 18.4083C20.2361 17.5527 19.2444 16.8819 18.1167 16.3958C16.9889 15.9097 15.7833 15.6666 14.5 15.6666C13.2167 15.6666 12.0111 15.9097 10.8833 16.3958C9.75556 16.8819 8.76389 17.5527 7.90833 18.4083ZM2.95 13.45L0.5 11C2.28889 9.17218 4.37917 7.74302 6.77083 6.71246C9.1625 5.6819 11.7389 5.16663 14.5 5.16663C17.2611 5.16663 19.8375 5.6819 22.2292 6.71246C24.6208 7.74302 26.7111 9.17218 28.5 11L26.05 13.45C24.5528 11.9527 22.8176 10.7814 20.8443 9.93596C18.8711 9.09052 16.7563 8.6674 14.5 8.66663C12.2437 8.66585 10.1293 9.08896 8.15683 9.93596C6.18439 10.783 4.44878 11.9543 2.95 13.45Z" fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_456_344">
                                            <rect width="28" height="28" fill="white" transform="translate(0.5 0.5)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <h1>Konektivitas <br> Perangkat IoT</h1>
                            </div>
                            <h1 id="online_status" class="text-4xl mt-4 lg:mt-0 w-fit mx-auto lg:mx-0">{{ $device->status ? 'Online' : 'Offline' }}</h1>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <h1>Durasi terhubung hari ini</h1>
                            <h1 id="online_duration">{{ $latest->online_duration ?? '-' }}</h1>
                        </div>
                        <div class="flex justify-between">
                            <h1>Update data terakhir</h1>
                            <h1 id="last_update">
                                {{ $latest?->created_at?->format('d-m-Y - H:i:s') ?? '-' }}
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <div class="bg-[#FFFFF0] rounded-[20px] p-11 mt-9">
                <div class="flex items-center justify-between mb-8 lg:ml-5">
                    <div class="flex gap-4 items-center">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6C8.93913 6 7.92172 6.42143 7.17157 7.17157C6.42143 7.92172 6 8.93913 6 10V30C6 31.0609 6.42143 32.0783 7.17157 32.8284C7.92172 33.5786 8.93913 34 10 34H30C31.0609 34 32.0783 33.5786 32.8284 32.8284C33.5786 32.0783 34 31.0609 34 30V10C34 8.93913 33.5786 7.92172 32.8284 7.17157C32.0783 6.42143 31.0609 6 30 6H10ZM20 20C20.2652 20 20.5196 20.1054 20.7071 20.2929C20.8946 20.4804 21 20.7348 21 21V27C21 27.2652 20.8946 27.5196 20.7071 27.7071C20.5196 27.8946 20.2652 28 20 28C19.7348 28 19.4804 27.8946 19.2929 27.7071C19.1054 27.5196 19 27.2652 19 27V21C19 20.7348 19.1054 20.4804 19.2929 20.2929C19.4804 20.1054 19.7348 20 20 20ZM12 17C12 16.7348 12.1054 16.4804 12.2929 16.2929C12.4804 16.1054 12.7348 16 13 16C13.2652 16 13.5196 16.1054 13.7071 16.2929C13.8946 16.4804 14 16.7348 14 17V27C14 27.2652 13.8946 27.5196 13.7071 27.7071C13.5196 27.8946 13.2652 28 13 28C12.7348 28 12.4804 27.8946 12.2929 27.7071C12.1054 27.5196 12 27.2652 12 27V17ZM27 12C27.2652 12 27.5196 12.1054 27.7071 12.2929C27.8946 12.4804 28 12.7348 28 13V27C28 27.2652 27.8946 27.5196 27.7071 27.7071C27.5196 27.8946 27.2652 28 27 28C26.7348 28 26.4804 27.8946 26.2929 27.7071C26.1054 27.5196 26 27.2652 26 27V13C26 12.7348 26.1054 12.4804 26.2929 12.2929C26.4804 12.1054 26.7348 12 27 12Z" fill="black" />
                        </svg>
                        <h1>Tren Kualitas Lahan</h1>
                    </div>
                </div>
                <div class="relative lg:h-[480px] h-90">
                    <canvas id="myChart"></canvas>
                    <div id="chartTooltip"></div>
                </div>
            </div>

            <section class="mt-9">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-9">

                    <div class="bg-[#FFFFF0] rounded-[20px] text-3xl p-11">
                        <div class="flex items-center gap-6 mb-8 lg:ml-5">
                            <div class="p-4 bg-[#80B56F] rounded-full">
                                <svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_907_62)">
                                        <path d="M18.8125 4.40375C17.98 5.225 17.7662 6.40625 18.16 7.41875L14.875 10.7038V8.375C14.875 7.75625 14.3687 7.25 13.75 7.25H12.5912C12.625 7.05875 12.625 6.87875 12.625 6.6875C12.625 3.2675 9.85748 0.500003 6.43748 0.500003C5.16307 0.498808 3.91941 0.891369 2.87663 1.62399C1.83385 2.3566 1.04287 3.3935 0.611901 4.59283C0.180931 5.79216 0.131016 7.09535 0.468984 8.32413C0.806951 9.55291 1.5163 10.6473 2.49998 11.4575V18.5C2.49998 19.1188 3.00623 19.625 3.62498 19.625H13.75C14.3687 19.625 14.875 19.1188 14.875 18.5V13.8763L19.7462 9.005C20.7587 9.39875 21.94 9.19625 22.75 8.375L18.8125 4.40375ZM2.55623 7.25C2.53373 7.05875 2.49998 6.87875 2.49998 6.6875C2.49998 4.51625 4.26623 2.75 6.43748 2.75C8.60873 2.75 10.375 4.51625 10.375 6.6875C10.375 6.87875 10.3412 7.05875 10.3187 7.25M12.625 17.375H4.74998V9.5H12.625V17.375Z" fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_907_62">
                                            <rect width="23" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>

                            <h1 class="text-xl lg:text-2xl">Aktivitas Penyiraman <span class="hidden lg:inline-block">Lahan</span></h1>
                        </div>

                        <div class="flex items-center justify-between mb-5">
                            <div class="">
                                <h1>{{ $device->today_activity->count() }} <span class="text-base">Kali</span></h1>
                                <p class="text-gray-400 text-sm">Penyiraman <span class="hidden lg:inline-block">hari ini</span></p>
                            </div>
                            <div class="text-center">
                                <h1>{{ round($device->today_activity->sum('duration')/60) }} <span class="text-base hidden lg:inline-block">Menit</span> <span class="text-base inline-block lg:hidden">m</span></h1>
                                <p class="text-gray-400 text-sm">Durasi <span class="hidden lg:inline-block">Penyiraman</span></p>
                            </div>
                            <div class="text-end">
                                <h1>{{ round($device->today_activity->sum('duration')) }} <span class="text-base hidden lg:inline-block">Liter</span><span class="text-base inline-block lg:hidden">L</span></h1>
                                <p class="text-gray-400 text-sm">Volume <span class="hidden lg:inline-block">Penyiraman</span></p>
                            </div>

                        </div>


                        <div class="h-[380px] overflow-y-auto activity-log-scroll pr-3">
                            @forelse($device->siram_activity_logs->take(5) as $activity_log)

                            @if($loop->iteration ==1)
                            <hr class="mb-5 text-gray-400">
                            @endif

                            <div class="flex justify-between items-center lg:mx-5">
                                <div class="flex items-center gap-4">
                                    <h1 class="py-2 px-4 rounded-full bg-[#80CC94]/50 text-xl text-[#FFFFF0] font-bold">
                                        {{ $loop->iteration }}
                                    </h1>
                                    <div>
                                        <h1 class="text-xl">Penyiraman {{ $activity_log->mode }}</h1>
                                        <p class="text-base text-gray-400 hidden lg:block">Penyiraman karena {{ $activity_log->desc() }}</p>
                                    </div>
                                </div>
                                <div class="text-right text-base text-gray-400 hidden lg:block">
                                    <p>{{ $activity_log->created_at->locale('id')->diffForHumans() }}</p>
                                    <p>{{ $activity_log->created_at->locale('id')->translatedFormat('j M - H:i:s') }}</p>
                                </div>
                            </div>
                            <p class="text-base text-gray-400 mt-2 lg:hidden">Penyiraman karena {{ $activity_log->desc() }}</p>
                            <div class="flex justify-between text-right text-base text-gray-400 lg:hidden">
                                <!-- <p>Baru Saja</p> -->
                                <p>{{ $activity_log->created_at->format('j l - H:i:s') }}</p>
                            </div>

                            <hr class="my-5 text-gray-400">
                            @empty
                            <h1 class="text-base text-gray-400 text-center">Belum ada log aktivitas penyiraman perangkat</h1>
                            @endforelse
                        </div>

                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] text-3xl p-11">
                        <div class="flex items-center gap-6 mb-8 lg:ml-5">
                            <svg width="53" height="53" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-3 bg-[#80B56F] rounded-full">
                                <path d="M15.125 28.875V19.8L8.73125 26.2281L6.77188 24.2687L13.2 17.875H4.125V15.125H13.2L6.77188 8.73125L8.73125 6.77188L15.125 13.2V4.125H17.875V13.2L24.2687 6.77188L26.2281 8.73125L19.8 15.125H28.875V17.875H19.8L26.2281 24.2687L24.2687 26.2281L17.875 19.8V28.875H15.125Z" fill="white" />
                            </svg>
                            <h1>Log Aktivitas <span class="hidden lg:inline-block">Perangkat</span></h1>
                        </div>

                        @foreach($device->devices_logs as $log)

                        <div class="flex justify-between items-center lg:mx-5">
                            <div class="flex items-center gap-4">
                                <h1 class="py-3 px-2 rounded-xl bg-[#80CC94] text-base text-[#FFFFF0] font-bold">
                                    {{ $log->created_at->format('j/m') }}
                                </h1>
                                <div>
                                    <h1 class="text-xl">Sawah - 1</h1>
                                    <p class="text-base text-gray-400 hidden lg:block">{{ $log->activity }}</p>
                                </div>
                            </div>
                            <div class="text-right text-base text-gray-400 hidden lg:block">
                                <!-- <p>Baru Saja</p> -->
                                <p>{{ $log->created_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                        <p class="text-base text-gray-400 mt-2 lg:hidden">{{ $log->activity }}</p>
                        <div class="flex justify-between text-right text-base text-gray-400 lg:hidden">
                            <!-- <p>Baru Saja</p> -->
                            <p>{{ $log->created_at->format('H:i:s') }}</p>
                        </div>

                        <hr class="my-5 text-gray-400">

                        @endforeach

                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer class="bg-[#FFFFF0] flex justify-center">
        <h1 class="py-5 text-xl">2026 ©&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Virion</h1>
    </footer>
</body>

<script>
    function externalTooltipHandler(context) {
        const {
            chart,
            tooltip
        } = context;
        const tooltipEl = document.getElementById('chartTooltip');

        if (tooltip.opacity === 0) {
            tooltipEl.style.opacity = 0;
            return;
        }

        if (tooltip.body) {
            const dataPoint = tooltip.dataPoints[0];
            const label = chart.data.labels[dataPoint.dataIndex];
            const value = dataPoint.formattedValue;

            tooltipEl.innerHTML = `
                <div class="tt-date">${label}</div>
                <div class="tt-row">
                    <div class="tt-bar"></div>
                    <div>
                        <div class="tt-label">Kelembapan</div>
                        <div class="tt-value">${value} %</div>
                    </div>
                </div>
            `;
        }

        const {
            offsetLeft: positionX,
            offsetTop: positionY
        } = chart.canvas;
        tooltipEl.style.opacity = 1;
        tooltipEl.style.left = positionX + tooltip.caretX + 12 + 'px';
        tooltipEl.style.top = positionY + tooltip.caretY - 20 + 'px';
    }

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Kelembapan',
                data: [],
                borderColor: '#03D076',
                backgroundColor: '#03D076',
                borderWidth: 1.5,
                pointRadius: 0, // sembunyikan titik saat data padat
                pointHoverRadius: 5, // tetap muncul saat hover
                pointHitRadius: 8, // area sentuh/hover diperlebar
                pointBackgroundColor: 'rgb(255,255,255)',
                tension: 0.3, // sedikit smoothing biar tidak zig-zag tajam
                borderRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 8,
                        boxHeight: 8,
                    }
                },
                tooltip: {
                    enabled: false,
                    external: externalTooltipHandler
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true
                    },
                    ticks: {
                        autoSkip: false, // kontrol manual lewat callback, bukan autoSkip bawaan
                        callback: function(val, index) {
                            const totalLabels = this.getLabels().length;
                            const maxLabels = 5; // maksimum label yang ditampilkan
                            const step = Math.ceil(totalLabels / maxLabels);
                            return index % step === 0 ? this.getLabelForValue(val) : '';
                        },
                        maxRotation: 0,
                        minRotation: 0
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Kelembapan'
                    }
                }
            }
        }
    });

    function loadChart() {
        fetch("{{ route('chart.get',['virdi_type' => $device->virdi_type, 'device_id' => $device->id , 'periode' => 'daily']) }}")
            .then(response => response.json())
            .then(data => {
                const values_hum = data.map(row => row.humidity);
                const labels = data.map(row => {
                    const date = new Date(row.created_at);
                    return date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                });

                myChart.data.labels = labels;
                myChart.data.datasets[0].data = values_hum;
                myChart.update();
            })
            .catch(error => console.error('Error:', error));
    }
    loadChart();

    setInterval(loadChart, 120000);
</script>

<script>
    function formatDateTime(dateStr) {
        const date = new Date(dateStr);
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        const h = String(date.getHours()).padStart(2, '0');
        const min = String(date.getMinutes()).padStart(2, '0');
        const s = String(date.getSeconds()).padStart(2, '0');
        return `${d}-${m}-${y} - ${h}:${min}:${s}`;
    }


    function loadData() {
        fetch("{{ route('chart.get',['virdi_type' => $device->virdi_type, 'device_id' => $device->id , 'periode' => 'now']) }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('humidity').textContent = data.siram_sensors[0].humidity + "%";
                document.getElementById('temperature').textContent = data.siram_sensors[0].temperature;
                document.getElementById('online_status').textContent = data.status === 1 ? 'online' : 'offline';
                document.getElementById('online_duration').textContent = data.siram_sensors[0].online_duration;
                document.getElementById('last_update').textContent = formatDateTime(data.siram_sensors[0].created_at);
            })
    }

    loadData();

    setInterval('loadData', 120000);
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownBtn = document.getElementById("dropdownRadioHelperButton");
        const dropdownMenu = document.getElementById("dropdownRadioHelper");

        // Toggle dropdown saat tombol ditekan
        dropdownBtn.addEventListener("click", function(e) {
            e.stopPropagation(); // biar event tidak bubbling ket
            dropdownMenu.classList.toggle("hidden");
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener("click", function(e) {
            if (!dropdownMenu.classList.contains("hidden") && !dropdownMenu.contains(e.target) && e.target !== dropdownBtn) {
                dropdownMenu.classList.add("hidden");
            }
        });

        // Opsional: Tambahkan event listener ke radio button
        const radios = dropdownMenu.querySelectorAll("input[name='helper-radio']");
        radios.forEach(radio => {
            radio.addEventListener("change", function() {
                console.log("Selected:", this.id); // Bisa ganti dengan logika lain
                dropdownMenu.classList.add("hidden"); // Tutup dropdown setelah pilih
            });
        });
    });
</script>

<script>
    // sidebar
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebar-close');
    const overlay = document.getElementById('overlay');

    hamburger.addEventListener("click", () => {
        sidebar.classList.add('translate-x-0');
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-40');
    })

    sidebarClose.addEventListener("click", () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    })

    overlay.addEventListener("click", () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        form.classList.add('hidden');
        overlay.classList.add('opacity-40');
        scheduleForm.classList.add('hidden');
        scheduleDetailCard.classList.add('hidden');
    })

    // form config (threshold / size)
    const configOpen = document.getElementById('change-threshold');
    const formThresClose = document.getElementById('form-exit');
    const form = document.getElementById('threshold-form');

    configOpen.addEventListener("click", () => {
        overlay.classList.remove('hidden');
        form.classList.remove('hidden');
    })

    formThresClose.addEventListener("click", () => {
        overlay.classList.add('hidden');
        form.classList.add('hidden');
    })

    const scheduleFormOpen = document.getElementById('form-schedule-open');
    const scheduleFormClose = document.getElementById('form-schedule-exit');
    const scheduleForm = document.getElementById('schedule-form');

    scheduleFormOpen.addEventListener("click", () => {
        overlay.classList.remove('hidden');
        scheduleForm.classList.remove('hidden');
    })

    scheduleFormClose.addEventListener("click", () => {
        overlay.classList.add('hidden');
        scheduleForm.classList.add('hidden');
    })

    const scheduleDetailOpen = document.getElementById('schedule-detail-open');
    const scheduleDetailClose = document.getElementById('schedule-detail-close');
    const scheduleDetailCard = document.getElementById('schedule-detail-modal');

    scheduleDetailOpen.addEventListener("click", () => {
        overlay.classList.remove('hidden');
        scheduleDetailCard.classList.remove('hidden');
    })

    scheduleDetailClose.addEventListener("click", () => {
        overlay.classList.add('hidden');
        scheduleDetailCard.classList.add('hidden');
    })
</script>

<!-- form number spinner -->
<script>
    const btnUpDec = document.getElementById('up-thres-decrease');
    const btnUpIn = document.getElementById('up-thres-increase');
    const btnLowDec = document.getElementById('low-thres-decrease');
    const btnLowIn = document.getElementById('low-thres-increase');
    const lowThres = document.getElementById('low-threshold');
    const upThres = document.getElementById('up-threshold');

    btnUpDec.addEventListener("click", () => {
        upThres.value = parseInt(upThres.value) - 1;
    });

    btnUpIn.addEventListener("click", () => {
        upThres.value = parseInt(upThres.value) + 1;
    });

    btnLowDec.addEventListener("click", () => {
        lowThres.value = parseInt(lowThres.value) - 1;
    });

    btnLowIn.addEventListener("click", () => {
        lowThres.value = parseInt(lowThres.value) + 1;
    });
</script>

<script>
    (function() {
        const input = document.getElementById('input-duration');
        const btnInc = document.getElementById('increase-duration');
        const btnDec = document.getElementById('decrease-duration');
        const form = document.getElementById('form-jadwal');

        const STEP = 0.5;
        const MIN = 0;
        const MAX = 120;

        // "1,5" / "1.5" -> 1.5 (number)
        function parseVal(str) {
            if (!str) return 0;
            const num = parseFloat(str.toString().replace(',', '.'));
            return isNaN(num) ? 0 : num;
        }

        // 1.5 -> "1,5"
        function formatVal(num) {
            return num.toFixed(1).replace('.', ',');
        }

        function setVal(num) {
            num = Math.min(MAX, Math.max(MIN, num));
            num = Math.round(num / STEP) * STEP; // snap ke kelipatan 0.5
            input.value = formatVal(num);
        }

        // format awal saat load
        setVal(parseVal(input.value));

        btnInc.addEventListener('click', () => setVal(parseVal(input.value) + STEP));
        btnDec.addEventListener('click', () => setVal(parseVal(input.value) - STEP));

        // batasi ketikan manual: hanya angka + 1 koma + 1 digit desimal
        input.addEventListener('input', () => {
            let v = input.value.replace(/[^0-9,]/g, '');
            const parts = v.split(',');
            if (parts.length > 2) v = parts[0] + ',' + parts.slice(1).join('');
            const p2 = v.split(',');
            if (p2[1] && p2[1].length > 1) v = p2[0] + ',' + p2[1].slice(0, 1);
            input.value = v;
        });

        // rapikan & clamp saat user selesai mengetik
        input.addEventListener('blur', () => setVal(parseVal(input.value)));

        // pastikan backend (Laravel) terima format titik, bukan koma
        form.addEventListener('submit', () => {
            input.value = parseVal(input.value).toFixed(1);
        });
    })();
</script>

</html>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>{{ $data->name }}</title>
    <link rel="shortcut icon" href="{{ asset('images/Logo.png') }}" type="image/x-icon">
</head>

<x-beranda.side-bar :devices="$devices" />

<!-- form resize pelet -->
<aside id="pelet-form" class="hidden">
    <div
        class="fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.3646 22.9167L15.7812 19.5833C16.0069 19.4965 16.2194 19.3924 16.4187 19.2708C16.6181 19.1493 16.8135 19.0191 17.0052 18.8802L20.1042 20.1823L22.9688 15.2344L20.2865 13.2031C20.3038 13.0816 20.3125 12.9646 20.3125 12.8521V12.149C20.3125 12.0358 20.3038 11.9184 20.2865 11.7969L22.9688 9.76562L20.1042 4.81771L17.0052 6.11979C16.8142 5.9809 16.6146 5.85069 16.4062 5.72917C16.1979 5.60764 15.9896 5.50347 15.7812 5.41667L15.3646 2.08333H9.63542L9.21875 5.41667C8.99305 5.50347 8.78021 5.60764 8.58021 5.72917C8.38021 5.85069 8.18507 5.9809 7.99479 6.11979L4.89583 4.81771L2.03125 9.76562L4.71354 11.7969C4.69618 11.9184 4.6875 12.0358 4.6875 12.149V12.851C4.6875 12.9642 4.70486 13.0816 4.73958 13.2031L2.05729 15.2344L4.92188 20.1823L7.99479 18.8802C8.18576 19.0191 8.38542 19.1493 8.59375 19.2708C8.80208 19.3924 9.01042 19.4965 9.21875 19.5833L9.63542 22.9167H15.3646ZM12.4479 16.1458C11.441 16.1458 10.5816 15.7899 9.86979 15.0781C9.15799 14.3663 8.80208 13.5069 8.80208 12.5C8.80208 11.4931 9.15799 10.6337 9.86979 9.92187C10.5816 9.21007 11.441 8.85417 12.4479 8.85417C13.4722 8.85417 14.3361 9.21007 15.0396 9.92187C15.7431 10.6337 16.0944 11.4931 16.0937 12.5C16.0931 13.5069 15.7413 14.3663 15.0385 15.0781C14.3358 15.7899 13.4722 16.1458 12.4479 16.1458Z" fill="#1C1C1C" />
                </svg>
                <h1 class="text-lg lg:text-xl">Pengaturan ukuran pelet</h1>
            </div>
            <button id="form-exit" type="button" class="px-2 py-1 rounded-full hover:bg-[#D1D1C6] cursor-pointer">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p>Atur ukuran pelet agar pemberian pakan dapat sesuai dengan takaran yang ditentukan</p>
        <form action="{{ route('edit-feedSize') }}" method="POST">
            @csrf
            <div class="mt-4 lg:flex items-center justify-between">
                <div class="bg-white border border-gray-200 rounded-lg w-full" data-hs-input-number="">
                    <div class="w-full flex justify-between items-center gap-x-1">
                        <div class="grow py-2 px-3 flex">
                            <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0" type="number" name="feed_size"
                                aria-roledescription="Number field" step="0.1" min="0" max="99.9" value="{{ $data->feed_config->feed_size }}"
                                data-hs-input-number-input="" id="input">
                            <span class="text-gray-300">mm</span>
                        </div>
                        <div class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200">
                            <button type="button"
                                class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                id="decrease">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                </svg>
                            </button>
                            <button type="button"
                                class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                id="increase">
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

                <input type="hidden" name="device_id" value="{{ $data->id }}">
                <input type="hidden" name="serial_number" value="{{ $data->serial_number }}">
                <input type="hidden" name="name" value="{{ $data->name }}">

                <button type="submit" id="submit-form-pelet" class="ml-4 w-full lg:w-40 mt-6 lg:mt-0">
                    <h1
                        class="cursor-pointer border rounded-xl border-[#D1BE4F] text-base px-5 py-1 hover:text-[#FFFFF0] hover:bg-[#D1BE4F] flex justify-center">
                        Simpan</h1>
                </button>
            </div>
        </form>
    </div>
</aside>

<!-- Overlay -->
<aside id="overlay" class="hidden fixed top-0 right-0 left-0 bottom-0 z-10 bg-gray-600 opacity-40 cursor-pointer"></aside>

<!-- Form Add Schedule -->
<aside id="schedule-form" class="hidden">
    <div
        class="fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11 text-base">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                <img src="{{ asset('images/add.svg') }}" alt="">
                <h1 class="text-lg lg:text-xl">Penambahan jadwal pemberian pakan</h1>
            </div>
            <button id="form-schedule-exit" type="button" class="px-2 py-1 rounded-full hover:bg-[#D1D1C6] cursor-pointer">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p>Tambahkan jadwal baru untuk pemberian pakan ikan sesuai waktu dan dosis yang diinginkan</p>

        <form id="form-jadwal" action="{{ route('add-schedule', ['device_id' => $data->id]) }}" method="POST">
            @csrf
            <label for="portion" class="block mt-3 text-sm text-gray-900">Atur porsi pemberian pakan:</label>
            <div class="mt-4 lg:flex items-center justify-between">
                <div class="bg-white border border-gray-200 rounded-lg w-full" data-hs-input-number="">
                    <div class="w-full flex justify-between items-center gap-x-1">
                        <div class="grow py-2 px-3 flex">
                            <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0" type="number" name="portion"
                                aria-roledescription="Number field" step="0.01" min="0" max="99.99" value="{{ $data->feed_config->feed_size }}"
                                data-hs-input-number-input="" id="input-portion">
                            <span class="text-gray-300">Kg</span>
                        </div>
                        <div class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200">
                            <button type="button"
                                class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                id="decrease-portion">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                </svg>
                            </button>
                            <button type="button"
                                class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                                id="increase-portion">
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

            <!-- Label waktu -->
            <label for="time" class="block mb-2 mt-3 text-sm text-gray-900">Pilih waktu pemberian pakan:</label>

            <div class="relative bg-white border border-gray-200 rounded-lg w-full">
                <div class="flex items-center justify-between px-3 py-2">
                    <input
                        type="time"
                        id="time"
                        name="time"
                        class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0 focus:outline-none leading-none"
                        min="00:00"
                        max="24:00"
                        value="00:00"
                        required />
                </div>
            </div>

            <!-- Checkbox Hari -->
            <label for="days[]" class="block mb-2 mt-3 text-sm text-gray-900">Pilih hari penjadwalan pemberian pakan:</label>
            <div class="flex mt-3 gap-4 justify-center">
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="senin"
                        type="checkbox"
                        value="senin"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Senin</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="selasa"
                        type="checkbox"
                        value="selasa"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Selasa</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="rabu"
                        type="checkbox"
                        value="rabu"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Rabu</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="kamis"
                        type="checkbox"
                        value="kamis"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Kamis</span>
                </label>
            </div>
            <div class="flex mt-3 gap-4 justify-center">
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="jumat"
                        type="checkbox"
                        value="jumat"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Jumat</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="sabtu"
                        type="checkbox"
                        value="sabtu"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Sabtu</span>
                </label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        name="days[]"
                        id="minggu"
                        type="checkbox"
                        value="minggu"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0 focus:outline-none">
                    <span class="text-sm text-gray-900">Minggu</span>
                </label>
            </div>

            <input type="hidden" name="name" value="{{ $data->name }}">

            <button id="btn-submit-tambah-jadwal" type="submit"
                class="cursor-pointer mt-3 w-full border border-[#D1BE4F] hover:bg-[#D1BE4F] text-gray-900 hover:text-[#FFFFF0] rounded-xl">
                <h1 class="py-1.5">Tambahkan</h1>
            </button>
        </form>
    </div>
</aside>

<!-- Navbar -->
<nav class="flex justify-between items-center mb-11 text-2xl mx-8 lg:mx-20 pt-8">
    <div class="flex  items-center gap-5 lg:gap-11">
        <button type="button" id="hamburger" class="cursor-pointer hover:bg-[#D1D1C6] rounded-full px-2 py-1">
            <i class="bi bi-list text-3xl"></i>
        </button>
        <div class="flex  items-center gap-4">
            <img src="{{ asset('images/Logo.png') }}" alt="" class="size-14 hidden lg:block">
            <h1 class="hidden lg:block">Virion&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Dashboard
                <span>{{ $data->name }}</span>
            </h1>
            <h1 class="lg:hidden">Dashboard <span>{{ $data->name }}</span></h1>
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

<body class="bg-[#F4F7F3]">
    <main class="mx-8 lg:mx-20 text-2xl mb-10">
        <section class="text-sm text-[#979797]">
            <div class="grid grid-cols-1 lg:grid-cols-3 lg:grid-rows-2 gap-9 ">
                <div class="row-span-2 bg-[#FFFFF0] rounded-[20px] p-10">
                    <div class="block lg:flex items-center justify-between mb-6 text-gray-800">
                        <div class="flex items-center gap-4 text-xl">
                            <div class=" bg-[#D1BE4F] rounded-full">
                                <svg width="53" height="53" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-3">
                                    <path d="M7.50903 15H7.49854" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.75 20.0522C14.3294 20.2434 14.8845 20.4635 15.3874 20.8156M15.3874 20.8156C16.6884 21.7264 17.5 23.2205 17.5 24.8556C17.5 24.9355 17.4331 25.0004 17.3524 25C13.706 24.9839 12.073 24.3665 11.3867 23.3477L10 21.071C6.8856 20.4421 4.02285 18.4531 2.5 15.1041C6.25 6.85736 18.125 6.85736 21.875 15.1041M15.3874 20.8156C18.1001 19.9902 20.5189 18.0865 21.875 15.1041M21.875 15.1041C22.2916 14.2795 24.5 11.3931 27.5 11.3931C26.4584 12.4239 24.75 16.3411 26.25 18.8152C24.75 18.8152 22.5 15.9289 21.875 15.1041ZM15.3874 9.39275C16.6884 8.48205 17.5 6.9878 17.5 5.35272C17.5 4.3195 12.1146 5.78007 11.3867 6.86057L10 9.13727" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h1>Status Pemberian <br>Pakan Terakhir</h1>
                        </div>
                        <h1 class="text-4xl mt-4 lg:mt-0 w-fit mx-auto lg:mx-0">Berhasil</h1>
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-between">
                        <h1>Pemberian berhasil hari ini</h1>
                        <h1>{{ $data->feed_config->success_daily + $data->feed_config->manual_daily }} / {{ $data->feed_config->total_daily + $data->feed_config->manual_daily }}</h1>
                    </div>
                    <div class="flex justify-between">
                        <h1>Pemberian berhasil minggu ini</h1>
                        <h1>{{ $data->feed_config->success_weekly + $data->feed_config->manual_weekly }} / {{ $data->feed_config->total_weekly + $data->feed_config->manual_weekly }}</h1>
                    </div>
                    <hr class="my-4">

                    <div class="block lg:flex  items-center justify-between mb-4 text-gray-800 text-lg">
                        <div class="flex items-center gap-4">
                            <svg width="35" height="35" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_456_344)">
                                    <path d="M14.5 25C13.6833 25 12.9931 24.718 12.4292 24.1541C11.8653 23.5902 11.5833 22.9 11.5833 22.0833C11.5833 21.2666 11.8653 20.5764 12.4292 20.0125C12.9931 19.4486 13.6833 19.1666 14.5 19.1666C15.3167 19.1666 16.0069 19.4486 16.5708 20.0125C17.1347 20.5764 17.4167 21.2666 17.4167 22.0833C17.4167 22.9 17.1347 23.5902 16.5708 24.1541C16.0069 24.718 15.3167 25 14.5 25ZM7.90833 18.4083L5.45833 15.9C6.60556 14.7527 7.95228 13.8439 9.4985 13.1735C11.0447 12.503 12.7119 12.1674 14.5 12.1666C16.2881 12.1658 17.9557 12.5061 19.5027 13.1875C21.0497 13.8688 22.396 14.7924 23.5417 15.9583L21.0917 18.4083C20.2361 17.5527 19.2444 16.8819 18.1167 16.3958C16.9889 15.9097 15.7833 15.6666 14.5 15.6666C13.2167 15.6666 12.0111 15.9097 10.8833 16.3958C9.75556 16.8819 8.76389 17.5527 7.90833 18.4083ZM2.95 13.45L0.5 11C2.28889 9.17218 4.37917 7.74302 6.77083 6.71246C9.1625 5.6819 11.7389 5.16663 14.5 5.16663C17.2611 5.16663 19.8375 5.6819 22.2292 6.71246C24.6208 7.74302 26.7111 9.17218 28.5 11L26.05 13.45C24.5528 11.9527 22.8176 10.7814 20.8443 9.93596C18.8711 9.09052 16.7563 8.6674 14.5 8.66663C12.2437 8.66585 10.1293 9.08896 8.15683 9.93596C6.18439 10.783 4.44878 11.9543 2.95 13.45Z" fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_456_344">
                                        <rect width="28" height="28" fill="black" transform="translate(0.5 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <h1>Konektivitas <br> Perangkat IoT</h1>
                        </div>
                        <h1 class="text-2xl mt-4 lg:mt-0 w-fit mx-auto lg:mx-0">Online</h1>
                    </div>
                    <div class="flex justify-between">
                        <h1>Durasi terhubung hari ini</h1>
                        <h1>{{ $data->feed_storages[0]->online_duration ?? 0}}</h1>
                    </div>
                    <div class="flex justify-between">
                        <h1>Update data terakhir</h1>
                        <h1>{{ $data->feed_storages->first()?->created_at?->format('H:i:s d-m-Y') ?? '-' }}</h1>
                    </div>

                    <hr class="my-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-gray-800 text-base">Mode Feeder</h1>
                            <p class="hidden lg:block">Atur mode feeder anda</p>
                        </div>
                        <x-monitoring.mode-select :mode="$data->feed_config->mode" :name="$data->name" :id="$data->id" :virdi_type="$data->virdi_type" color="border-[#D1BE4F] hover:bg-[#D1BE4F]" />
                    </div>
                </div>
                <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                    <div class="block lg:flex items-center justify-between mb-6 text-gray-800">
                        <div class="flex items-center gap-4 text-xl">
                            <div class="p-3 bg-[#D1BE4F] rounded-full">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.1666 17.5V22.0371C22.1666 24.6328 21.3084 25.6667 18.537 25.6667H9.46288C6.86712 25.6667 5.83325 24.8085 5.83325 22.0371V17.5C5.83325 12.9897 9.4896 9.33337 13.9999 9.33337C18.5103 9.33337 22.1666 12.9897 22.1666 17.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M18.6666 17.5C18.6666 14.9228 16.5772 12.8334 13.9999 12.8334C11.4226 12.8334 9.33325 14.9228 9.33325 17.5" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14 19.8333L15.1667 17.5" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M19.0312 5.83337H8.9689C7.93804 5.83337 7.4226 5.83337 6.96906 5.65599C6.81841 5.59708 6.6743 5.5242 6.53885 5.43843C6.13107 5.18025 5.84516 4.77502 5.27335 3.96455C4.82769 3.33292 4.60487 3.01709 4.68168 2.75886C4.706 2.67716 4.74887 2.60144 4.80733 2.53702C4.99212 2.33337 5.39382 2.33337 6.19723 2.33337H21.803C22.6064 2.33337 23.008 2.33337 23.1928 2.53702C23.2513 2.60144 23.2942 2.67716 23.3185 2.75886C23.3953 3.01709 23.1724 3.33292 22.7269 3.96455C22.155 4.77501 21.8691 5.18026 21.4613 5.43843C21.3258 5.5242 21.1817 5.59708 21.0311 5.65599C20.5775 5.83337 20.0621 5.83337 19.0312 5.83337Z" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M16.3334 9.33337V5.83337M11.6667 9.33337V5.83337" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <h1>Sisa Pakan <br> Ikan</h1>
                        </div>
                        <h1 class="text-4xl mx-auto w-fit mt-4 lg:mt-0 lg:mx-0">{{ $data->feed_storages->first()?->storage }} Kg</h1>
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-between">
                        <h1>Estimasi habis dalam</h1>
                        <h1>{{ $estimation}} hari</h1>
                    </div>
                    <div class="flex justify-between">
                        <h1>Terakhir isi ulang</h1>
                        <h1>{{ date('d M Y', strtotime($data->feed_config->last_refill)) ?? '-' }}</h1>
                    </div>
                </div>
                <div class="row-span-2 bg-[#FFFFF0] rounded-[20px] p-10">
                    <div class="flex items-center justify-between mb-6 text-gray-800">
                        <div class="flex items-center gap-4 text-xl">
                            <div class="p-3 bg-[#D1BE4F] rounded-full">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.6666 2.33337V7.00004M9.33325 2.33337V7.00004" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.1667 4.66663H12.8333C8.43355 4.66663 6.23367 4.66663 4.86683 6.03346C3.5 7.4003 3.5 9.60018 3.5 14V16.3333C3.5 20.733 3.5 22.933 4.86683 24.2998C6.23367 25.6666 8.43355 25.6666 12.8333 25.6666H15.1667C19.5664 25.6666 21.7664 25.6666 23.1331 24.2998C24.5 22.933 24.5 20.733 24.5 16.3333V14C24.5 9.60018 24.5 7.4003 23.1331 6.03346C21.7664 4.66663 19.5664 4.66663 15.1667 4.66663Z" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M3.5 11.6666H24.5" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.9947 16.3334H14.0052M13.9947 21H14.0052M18.6561 16.3334H18.6666M9.33325 16.3334H9.34372M9.33325 21H9.34372" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h1 class="text-lg lg:text-xl">Jadwal <br> Pemberian Pakan</h1>
                        </div>
                        <i id="schedule-open"
                            class="bi bi-three-dots-vertical cursor-pointer text-xl px-2 py-1 rounded-full hover:bg-[#D1BE4F] hover:text-[#FFFFF0]"></i>
                    </div>
                    <hr class="my-4">
                    <div class="lg:h-[285px] lg:overflow-auto">
                        @foreach($data->feed_schedules as $schedule)
                        <div class="p-3 mb-3 rounded-2xl flex items-center justify-between bg-[rgba(209,190,79,0.08)] text-gray-800">
                            <div>
                                <h1 class="text-2xl">{{ date('H:i', strtotime($schedule->time)) }} - {{ $schedule->portion }} <span class="text-lg">Kg</span></h1>
                                <h1>
                                    @foreach($schedule->dayCrop as $day)
                                    {{ $day . " " }}
                                    @endforeach
                                </h1>
                            </div>
                            <div class="flex items-center gap-3">
                                <form action="{{ route('change-scheduleStatus') }}" method="POST">
                                    @csrf
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="toggle_status" class="sr-only peer" {{ $schedule->active_status ? 'checked' : '' }} onchange="this.form.submit()">
                                        <input type="hidden" name="id" value="{{ $schedule->id }}">
                                        <input type="hidden" name="device_id" value="{{ $data->id }}">
                                        <input type="hidden" name="active_status" value="{{ $schedule->active_status ? (int) 0 : (int) 1 }}">
                                        <div class="relative w-14 h-7 bg-gray-200 rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition- peer-checked:bg-[#D1BE4F]"></div>
                                    </label>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                    <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.3646 22.9167L15.7812 19.5833C16.0069 19.4965 16.2194 19.3924 16.4187 19.2708C16.6181 19.1493 16.8135 19.0191 17.0052 18.8802L20.1042 20.1823L22.9688 15.2344L20.2865 13.2031C20.3038 13.0816 20.3125 12.9646 20.3125 12.8521V12.149C20.3125 12.0358 20.3038 11.9184 20.2865 11.7969L22.9688 9.76562L20.1042 4.81771L17.0052 6.11979C16.8142 5.9809 16.6146 5.85069 16.4062 5.72917C16.1979 5.60764 15.9896 5.50347 15.7812 5.41667L15.3646 2.08333H9.63542L9.21875 5.41667C8.99305 5.50347 8.78021 5.60764 8.58021 5.72917C8.38021 5.85069 8.18507 5.9809 7.99479 6.11979L4.89583 4.81771L2.03125 9.76562L4.71354 11.7969C4.69618 11.9184 4.6875 12.0358 4.6875 12.149V12.851C4.6875 12.9642 4.70486 13.0816 4.73958 13.2031L2.05729 15.2344L4.92188 20.1823L7.99479 18.8802C8.18576 19.0191 8.38542 19.1493 8.59375 19.2708C8.80208 19.3924 9.01042 19.4965 9.21875 19.5833L9.63542 22.9167H15.3646ZM12.4479 16.1458C11.441 16.1458 10.5816 15.7899 9.86979 15.0781C9.15799 14.3663 8.80208 13.5069 8.80208 12.5C8.80208 11.4931 9.15799 10.6337 9.86979 9.92187C10.5816 9.21007 11.441 8.85417 12.4479 8.85417C13.4722 8.85417 14.3361 9.21007 15.0396 9.92187C15.7431 10.6337 16.0944 11.4931 16.0937 12.5C16.0931 13.5069 15.7413 14.3663 15.0385 15.0781C14.3358 15.7899 13.4722 16.1458 12.4479 16.1458Z" fill="#1C1C1C" />
                        </svg>
                        <h1 class="text-lg lg:text-xl">Pengaturan ukuran pelet</h1>
                    </div>
                    <p>Atur ukuran pelet agar pemberian pakan dapat sesuai dengan takaran yang ditentukan</p>
                    <div class="block lg:flex items-center justify-between text-4xl text-gray-800 mt-7">
                        <div class="text-3xl flex justify-center">
                            {{ $data->feed_config->feed_size }} mm
                        </div>
                        <button id="pelet-config" type="button" class="w-full lg:w-40">
                            <h1
                                class="cursor-pointer border rounded-xl border-[#D1BE4F] text-base px-5 py-1 hover:text-[#FFFFF0] hover:bg-[#D1BE4F] flex justify-center mt-4 lg:mt-0">
                                Ubah</h1>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-9">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-9">
                <div class="bg-[#FFFFF0] rounded-[20px] p-11">
                    <div class="flex items-center justify-between mb-8 lg:ml-5">
                        <div class="flex gap-4 items-center">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 6C8.93913 6 7.92172 6.42143 7.17157 7.17157C6.42143 7.92172 6 8.93913 6 10V30C6 31.0609 6.42143 32.0783 7.17157 32.8284C7.92172 33.5786 8.93913 34 10 34H30C31.0609 34 32.0783 33.5786 32.8284 32.8284C33.5786 32.0783 34 31.0609 34 30V10C34 8.93913 33.5786 7.92172 32.8284 7.17157C32.0783 6.42143 31.0609 6 30 6H10ZM20 20C20.2652 20 20.5196 20.1054 20.7071 20.2929C20.8946 20.4804 21 20.7348 21 21V27C21 27.2652 20.8946 27.5196 20.7071 27.7071C20.5196 27.8946 20.2652 28 20 28C19.7348 28 19.4804 27.8946 19.2929 27.7071C19.1054 27.5196 19 27.2652 19 27V21C19 20.7348 19.1054 20.4804 19.2929 20.2929C19.4804 20.1054 19.7348 20 20 20ZM12 17C12 16.7348 12.1054 16.4804 12.2929 16.2929C12.4804 16.1054 12.7348 16 13 16C13.2652 16 13.5196 16.1054 13.7071 16.2929C13.8946 16.4804 14 16.7348 14 17V27C14 27.2652 13.8946 27.5196 13.7071 27.7071C13.5196 27.8946 13.2652 28 13 28C12.7348 28 12.4804 27.8946 12.2929 27.7071C12.1054 27.5196 12 27.2652 12 27V17ZM27 12C27.2652 12 27.5196 12.1054 27.7071 12.2929C27.8946 12.4804 28 12.7348 28 13V27C28 27.2652 27.8946 27.5196 27.7071 27.7071C27.5196 27.8946 27.2652 28 27 28C26.7348 28 26.4804 27.8946 26.2929 27.7071C26.1054 27.5196 26 27.2652 26 27V13C26 12.7348 26.1054 12.4804 26.2929 12.2929C26.4804 12.1054 26.7348 12 27 12Z" fill="black" />
                            </svg>
                            <h1>Grafik Sisa Pakan</h1>
                        </div>
                    </div>
                    <div class="relative lg:h-[480px] h-90">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <div class="bg-[#FFFFF0] rounded-[20px] text-3xl p-11">
                    <div class="flex items-center gap-6 mb-8 lg:ml-5">
                        <svg width="53" height="53" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-3 bg-[#D1BE4F] rounded-full">
                            <path d="M15.125 28.875V19.8L8.73125 26.2281L6.77188 24.2687L13.2 17.875H4.125V15.125H13.2L6.77188 8.73125L8.73125 6.77188L15.125 13.2V4.125H17.875V13.2L24.2687 6.77188L26.2281 8.73125L19.8 15.125H28.875V17.875H19.8L26.2281 24.2687L24.2687 26.2281L17.875 19.8V28.875H15.125Z" fill="white" />
                        </svg>
                        <h1>Log Aktivitas</h1>
                    </div>

                    @foreach($data->devices_logs as $log)

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
    <footer class="bg-[#FFFFF0] flex justify-center">
        <h1 class="py-5 text-xl">2025 ©&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Virion</h1>
    </footer>
</body>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sisa Pakan',
                data: [],
                groundColor: '#03D076',
                pointBackgroundColor: 'rgb(255,255,255)',
                pointRadius: 5,
                pointHoverRadius: 7,
                borderColor: '#03D076',
                borderWidth: 1,
                borderRadius: 6
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
                }
            },
            interaction: {
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true
                    },
                    ticks: {
                        callback: function(val, index) {
                            const maxLabels = 10; // maksimum label yang mau ditampilkan
                            const step = Math.ceil(this.getLabels().length / maxLabels);
                            return index % step === 0 ? this.getLabelForValue(val) : '';
                        }
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Sisa Pakan (Kg)'
                    }
                }
            }
        }
    });

    function loadChart() {
        fetch("{{ route('chart.get',['virdi_type' => $data->virdi_type, 'device_id' => $data->id , 'periode' => 'monthly']) }}")
            .then(response => response.json())
            .then(data => {
                const values_storage = data.map(row => row.storage);
                const labels = data.map(row => {
                    const date = new Date(row.created_at);
                    return date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                    });
                });

                myChart.data.labels = labels;
                myChart.data.datasets[0].data = values_storage;
                myChart.update();
            })
            .catch(error => console.error('Error:', error));
    }
    loadChart();

    setInterval(loadChart, 120000);
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
    })

    const peletConfigOpen = document.getElementById('pelet-config');
    const peletConfigClose = document.getElementById('form-exit');
    const form = document.getElementById('pelet-form');

    peletConfigOpen.addEventListener("click", () => {
        overlay.classList.remove('hidden');
        form.classList.remove('hidden');
    })

    peletConfigClose.addEventListener("click", () => {
        overlay.classList.add('hidden');
        form.classList.add('hidden');
    })

    const scheduleFormOpen = document.getElementById('schedule-open');
    const scheduleFormExit = document.getElementById('form-schedule-exit');
    const scheduleForm = document.getElementById('schedule-form');

    scheduleFormOpen.addEventListener("click", () => {
        overlay.classList.remove('hidden');
        scheduleForm.classList.remove('hidden');
    })

    scheduleFormExit.addEventListener("click", () => {
        overlay.classList.add('hidden');
        scheduleForm.classList.add('hidden');
    })
</script>

<script>
    const btnd = document.getElementById('decrease');
    const btni = document.getElementById('increase');
    const input = document.getElementById('input');

    btnd.addEventListener("click", () => {
        input.value = (parseFloat(input.value) - 0.1).toFixed(1);
    });

    btni.addEventListener("click", () => {
        input.value = (parseFloat(input.value) + 0.1).toFixed(1);
    });
</script>

<script>
    const btnd_por = document.getElementById('decrease-portion');
    const btni_por = document.getElementById('increase-portion');
    const input_por = document.getElementById('input-portion');

    btnd_por.addEventListener("click", () => {
        input_por.value = (parseFloat(input_por.value) - 0.1).toFixed(1);
    });

    btni_por.addEventListener("click", () => {
        input_por.value = (parseFloat(input_por.value) + 0.1).toFixed(1);
    });
</script>


</html>

</html>
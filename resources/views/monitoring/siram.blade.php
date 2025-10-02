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
</head>

<x-beranda.side-bar :devices="$devices" />

<body class="bg-[#F4F7F3]">

    <aside id="overlay" class="fixed top-0 right-0 left-0 bottom-0 z-10 bg-gray-600 opacity-40 hidden cursor-pointer">
    </aside>

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
            <p>Atur batas atas dan batas bawah kelembapan greenhouse anda</p>
            <div class="mt-4 lg:flex items-center justify-between">
                <div>
                    <div class="bg-white border border-gray-200 rounded-lg" data-hs-input-number="">
                        <div class="w-full flex justify-between items-center gap-x-1">
                            <form method="POST" action="" class="grow py-2 px-3 flex">
                                <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0"
                                    type="number" aria-roledescription="Number field" min="0" readonly value="40"
                                    data-hs-input-number-input="" id="up-threshold">
                                <span class="text-gray-300">%</span>
                            </form>
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

                    <div class="bg-white border border-gray-200 rounded-lg mt-4" data-hs-input-number="">
                        <div class="w-full flex justify-between items-center gap-x-1">
                            <form method="POST" action="" class="grow py-2 px-3 flex">
                                <input class="w-full p-0 bg-transparent border-0 text-gray-800 focus:ring-0"
                                    type="number" aria-roledescription="Number field" min="0" readonly value="50"
                                    data-hs-input-number-input="" id="low-threshold">
                                <span class="text-gray-300">%</span>
                            </form>
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
                <button type="submit" id="submit-form-humidity" class="w-full lg:w-40 mt-6 lg:mt-0">
                    <h1
                        class="cursor-pointer border rounded-xl border-[#80B56F] text-base px-5 py-1 hover:text-[#FFFFF0] hover:bg-[#80B56F] flex justify-center">
                        Simpan</h1>
                </button>
            </div>
        </div>
    </aside>

    <!-- navbar -->
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
                                <h1>Indeks Kualitas <br> Udara Greenhouse</h1>
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
                                <h1 class="text-gray-800 text-base">Mode Humidifier</h1>
                                <p class="hidden lg:block">Atur mode humidifier anda</p>
                            </div>
                            <div class="relative inline-block">
                                <button id="dropdownRadioHelperButton" data-dropdown-toggle="dropdownRadioHelper"
                                    class="cursor-pointer text-gray-800 bg-[#FFFFF0] rounded-xl px-5 py-1.5 text-center inline-flex items-center border-[#80B56F] border hover:bg-[#80B56F] hover:text-[#FFFFF0]"
                                    type="button">Mode<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>

                                <!-- Dropdown menu -->
                                <div id="dropdownRadioHelper"
                                    class="absolute left-1/3 lg:left-1/2 -translate-x-1/2 mt-2 z-50 hidden bg-[#FFFFF0] divide-y divide-gray-100 rounded-lg shadow-sm w-60">
                                    <ul class="p-3 space-y-1 text-sm text-gray-700"
                                        aria-labelledby="dropdownRadioHelperButton">
                                        <li>
                                            <div class="flex p-2 rounded-sm hover:bg-gray-100">
                                                <div class="flex items-center h-5">
                                                    <input id="helper-radio-4" name="helper-radio" type="radio" value=""
                                                        class="w-4 h-4 text-gray-800 bg-[#FFFFF0] border-gray-300">
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label for="helper-radio-4" class="font-medium text-gray-900">
                                                        <div>Automatic</div>
                                                        <p id="helper-radio-text-4"
                                                            class="text-xs font-normal text-gray-500">
                                                            Menyalakan humidifier secara otomatis ketika dibutuhkan
                                                        </p>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex p-2 rounded-sm hover:bg-gray-100">
                                                <div class="flex items-center h-5">
                                                    <input id="helper-radio-5" name="helper-radio" type="radio" value=""
                                                        class="w-4 h-4 text-gray-800 bg-[#FFFFF0] border-gray-300">
                                                </div>
                                                <div class="ms-2 text-sm">
                                                    <label for="helper-radio-5" class="font-medium text-gray-900">
                                                        <div>Manual - On</div>
                                                        <p id="helper-radio-text-5"
                                                            class="text-xs font-normal text-gray-500">
                                                            Menyalakan humidifier sekarang
                                                        </p>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
                                <h1>Kelembapan <br> Udara</h1>
                            </div>
                            <h1 id="humidity" class="text-4xl mx-auto w-fit mt-4 lg:mt-0 lg:mx-0">{{ $latest->humidity ?? '-' }} %</h1>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <h1>Rata-rata hari ini</h1>
                            <h1>{{ round($daily->avg_daily_humidity ?? 0, 2) }} %</h1>
                        </div>
                        <div class="flex justify-between">
                            <h1>Rata-rata minggu ini</h1>
                            <h1>{{ round($weekly->avg_weekly_humidity ?? 0, 2) }} %</h1>
                        </div>
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="block lg:flex items-center justify-between mb-6 text-gray-800">
                            <div class="flex items-center gap-4 text-xl">
                                <div class="p-3 bg-[#80B56F] rounded-full">
                                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.9998 6.33337C10.9998 5.40512 11.3686 4.51488 12.025 3.8585C12.6813 3.20212 13.5716 2.83337 14.4998 2.83337C15.4281 2.83337 16.3183 3.20212 16.9747 3.8585C17.6311 4.51488 17.9998 5.40512 17.9998 6.33337V15.6667C18.9793 16.4013 19.7028 17.4254 20.0678 18.5941C20.4329 19.7627 20.421 21.0166 20.0338 22.178C19.6467 23.3395 18.9039 24.3498 17.9106 25.0656C16.9174 25.7815 15.7242 26.1667 14.4998 26.1667C13.2755 26.1667 12.0822 25.7815 11.089 25.0656C10.0958 24.3498 9.35301 23.3395 8.96585 22.178C8.57869 21.0166 8.56679 19.7627 8.93184 18.5941C9.29689 17.4254 10.0204 16.4013 10.9998 15.6667V6.33337ZM14.4998 5.16671C14.1904 5.16671 13.8937 5.28962 13.6749 5.50842C13.4561 5.72721 13.3332 6.02396 13.3332 6.33337V16.2909C13.3332 16.4957 13.2793 16.6968 13.1769 16.8742C13.0745 17.0515 12.9272 17.1988 12.7498 17.3012C12.0825 17.6863 11.5608 18.2809 11.2659 18.9928C10.9709 19.7046 10.9191 20.4939 11.1184 21.2382C11.3178 21.9825 11.7572 22.6402 12.3685 23.1093C12.9797 23.5784 13.7287 23.8326 14.4993 23.8326C15.2698 23.8326 16.0188 23.5784 16.6301 23.1093C17.2413 22.6402 17.6807 21.9825 17.8801 21.2382C18.0794 20.4939 18.0276 19.7046 17.7326 18.9928C17.4377 18.2809 16.916 17.6863 16.2487 17.3012C16.0715 17.1987 15.9245 17.0513 15.8223 16.874C15.7201 16.6967 15.6664 16.4955 15.6665 16.2909V6.33337C15.6665 6.02396 15.5436 5.72721 15.3248 5.50842C15.106 5.28962 14.8093 5.16671 14.4998 5.16671Z" fill="white" />
                                    </svg>
                                </div>
                                <h1>Temperature <br> Udara</h1>
                            </div>
                            <h1 id="temperature" class="text-4xl mx-auto w-fit mt-4 lg:mt-0 lg:mx-0">{{ $latest->temperature ?? '-' }} °C</h1>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <h1>Rata-rata hari ini</h1>
                            <h1>{{ round($daily->avg_daily_temperature ?? 0, 2) }} °C</h1>
                        </div>
                        <div class="flex justify-between">
                            <h1>Rata-rata minggu ini</h1>
                            <h1>{{ round($weekly->avg_weekly_temperature ?? 0, 2) }} °C</h1>
                        </div>
                    </div>
                    <div class="bg-[#FFFFF0] rounded-[20px] p-10">
                        <div class="flex items-center text-gray-800 gap-2 text-xl mb-2">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.3646 22.9167L15.7812 19.5833C16.0069 19.4965 16.2194 19.3924 16.4187 19.2708C16.6181 19.1493 16.8135 19.0191 17.0052 18.8802L20.1042 20.1823L22.9688 15.2344L20.2865 13.2031C20.3038 13.0816 20.3125 12.9646 20.3125 12.8521V12.149C20.3125 12.0358 20.3038 11.9184 20.2865 11.7969L22.9688 9.76562L20.1042 4.81771L17.0052 6.11979C16.8142 5.9809 16.6146 5.85069 16.4062 5.72917C16.1979 5.60764 15.9896 5.50347 15.7812 5.41667L15.3646 2.08333H9.63542L9.21875 5.41667C8.99305 5.50347 8.78021 5.60764 8.58021 5.72917C8.38021 5.85069 8.18507 5.9809 7.99479 6.11979L4.89583 4.81771L2.03125 9.76562L4.71354 11.7969C4.69618 11.9184 4.6875 12.0358 4.6875 12.149V12.851C4.6875 12.9642 4.70486 13.0816 4.73958 13.2031L2.05729 15.2344L4.92188 20.1823L7.99479 18.8802C8.18576 19.0191 8.38542 19.1493 8.59375 19.2708C8.80208 19.3924 9.01042 19.4965 9.21875 19.5833L9.63542 22.9167H15.3646ZM12.4479 16.1458C11.441 16.1458 10.5816 15.7899 9.86979 15.0781C9.15799 14.3663 8.80208 13.5069 8.80208 12.5C8.80208 11.4931 9.15799 10.6337 9.86979 9.92187C10.5816 9.21007 11.441 8.85417 12.4479 8.85417C13.4722 8.85417 14.3361 9.21007 15.0396 9.92187C15.7431 10.6337 16.0944 11.4931 16.0937 12.5C16.0931 13.5069 15.7413 14.3663 15.0385 15.0781C14.3358 15.7899 13.4722 16.1458 12.4479 16.1458Z" fill="#1C1C1C" />
                            </svg>
                            <h1 class="text-lg lg:text-xl">Pengaturan treshold kelembapan</h1>
                        </div>
                        <p>Atur batas atas dan batas bawah kelembapan greenhouse anda</p>
                        <div class="block lg:flex items-center justify-between text-4xl text-gray-800 mt-7">
                            <div class="text-3xl flex justify-center">
                                <span>{{ round($device->$config_table->lower_threshold, 1) }} %</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span>{{ round($device->$config_table->upper_threshold, 1) }} %</span>
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
                            <h1 id="last_update">{{ $latest?->created_at?->format('d-m-Y - H:i:s') ?? '-' }}</h1>

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
                            <svg width="53" height="53" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-3 bg-[#80B56F] rounded-full">
                                <path d="M15.125 28.875V19.8L8.73125 26.2281L6.77188 24.2687L13.2 17.875H4.125V15.125H13.2L6.77188 8.73125L8.73125 6.77188L15.125 13.2V4.125H17.875V13.2L24.2687 6.77188L26.2281 8.73125L19.8 15.125H28.875V17.875H19.8L26.2281 24.2687L24.2687 26.2281L17.875 19.8V28.875H15.125Z" fill="white" />
                            </svg>
                            <h1>Log Aktivitas</h1>
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
                                <p>Baru Saja</p>
                                <p>{{ $log->created_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                        <p class="text-base text-gray-400 mt-2 lg:hidden">{{ $log->activity }}</p>
                        <div class="flex justify-between text-right text-base text-gray-400 lg:hidden">
                            <p>Baru Saja</p>
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
                    label: 'Kelembapan',
                    data: [],
                    groundColor: '#03D076',
                    pointBackgroundColor: 'rgb(255,255,255)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderColor: '#03D076',
                    borderWidth: 1,
                    borderRadius: 6
                },
                {
                    label: 'Temperatur',
                    data: [],
                    groundColor: '#FFC42E',
                    pointBackgroundColor: 'rgb(255,255,255)',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderColor: '#FFC42E',
                    borderWidth: 1,
                    borderRadius: 6
                }
            ]
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
                        text: 'Temperatur dan Kelembapan'
                    }
                }
            }
        }
    });

    function loadChart() {
        fetch("{{ route('chart.get',['virdi_type' => $device->virdi_type, 'device_id' => $device->id , 'periode' => 'monthly']) }}")
            .then(response => response.json())
            .then(data => {
                const values_temp = data.map(row => row.temperature);
                const values_hum = data.map(row => row.humidity);
                const labels = data.map(row => {
                    const date = new Date(row.created_at);
                    return date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                    });
                });

                myChart.data.labels = labels;
                myChart.data.datasets[0].data = values_hum;
                myChart.data.datasets[1].data = values_temp;
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
                document.getElementById('humidity').textContent = data.siram_sensors[0].humidity;
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


</html>
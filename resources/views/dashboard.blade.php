<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Beranda</title>
    <link rel="shortcut icon" href="{{ asset('/Logo.png') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<!-- overlay -->
<aside id="overlay" class="hidden fixed top-0 right-0 left-0 bottom-0 z-20 bg-gray-600 opacity-40 cursor-pointer">
</aside>

<!-- form edit alat -->
<aside id="form-edit-alat"
    class="hidden fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11">
</aside>

<!-- form tambah alat -->
<aside id="form-tambah-alat"
    class="hidden fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11 text-gray-500">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/add.svg') }}" alt="">
            <h1 class="text-2xl text-gray-800">Tambahkan Alat</h1>
        </div>
        <button id="exit-form-tambah-alat" type="button"
            class="px-2 py-1 rounded-full hover:bg-[#D1D1C6] cursor-pointer">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <p class="mb-3">Masukkan serial number pada box untuk menambah alat</p>

    <form action="{{ route('add-owner') }}" method="POST">
        @csrf
        <label for="serial-num-input">Serial Number:</label>
        <input type="text" id="serial-num-input" placeholder="X-00000" require minlength="7" name="serial_number"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l block w-full p-2.5">
        <button id="btn-submit-tambah-alat" type="submit"
            class="cursor-pointer mt-3 w-full border border-[#4CAF50] hover:bg-[#4CAF50] text-gray-900 hover:text-[#FFFFF0] rounded-xl">
            <h1 class="py-2">Tambahkan</h1>
        </button>
    </form>
</aside>

<!-- form edit profile -->
<aside id="form-edit-profile"
    class="hidden fixed w-90 lg:w-150 bg-[#FFFFF0] top-1/2 right-1/2 -translate-y-1/2 translate-x-1/2 z-20 rounded-2xl p-11">
</aside>

<!-- Side Bar -->
<x-beranda.side-bar :devices="$user->devices" :iconMap="$iconMap" />

<body class="bg-[#F4F7F3]">
    <div class="mx-8 lg:mx-20 pt-8 text-2xl mb-10">
        <nav class="flex justify-between items-center mb-11">
            <div class="flex  items-center gap-5 lg:gap-11">
                <button type="button" id="hamburger" class="cursor-pointer hover:bg-[#D1D1C6] rounded-full px-2 py-2">
                    <i class="bi bi-list text-3xl"></i>
                </button>
                <div class="flex  items-center gap-2">
                    <img src="{{ asset('images/Logo.png') }}" alt="" class="size-14 hidden lg:block">
                    <h1 class="hidden lg:block">Virion&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;Selamat Datang,
                        <span>{{ Auth::user()->name }}</span>
                    </h1>
                    <h1 class="lg:hidden">Selamat Datang, <span>{{ Auth::user()->name }}</span></h1>
                </div>
            </div>
            <div class="hidden lg:block">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();"
                        class="flex items-center text-xl gap-4 px-8 py-3 rounded-full bg-[#D1D1C6] hover:bg-[#b8b8ae]">
                        <img src="{{ asset('images/logout.svg') }}" alt="" class="h-full object-cover">
                        <h1>
                            Logout
                        </h1>
                    </a>
                </form>
            </div>
        </nav>

        <main class="lg:flex gap-x-6 my-11">
            <!-- Section Profile -->
            <section class="lg:w-5/12 bg-[#FFFFF0] rounded-3xl p-3">
                <img src="{{ asset('images/header.png') }}" alt="" class="rounded-2xl w-full">
                <div class="pl-7 mb-12 relative h-fit">
                    <div
                        class="absolute -top-0 -translate-y-1/2 z-10 rounded-full bg-[#FFFFF0] flex justify-center items-center">
                        <img src="{{ asset('images/profile_pict.png') }}" alt="" class="rounded-full p-2.5">
                    </div>
                    <a href="{{ route('profile.edit') }}">
                        <button type="button" id="btn-edit-profile"
                            class="absolute top-2 right-0 h-fit p-2 rounded-full hover:bg-gray-200 mr-4 cursor-pointer">
                            <img src="{{ asset('images/setting-abu.svg') }}" alt="" class="">
                        </button>
                    </a>
                </div>
                <div class="p-5 mb-10 text-[#979797] text-xl">
                    <div class="md:flex justify-between">
                        <div>
                            <h1 class="text-2xl text-gray-800">{{ Auth::user()->name }}</h1>
                            <h1>{{ Auth::user()->email }}</h1>
                            <h1>{{ Auth::user()->nomor_hp }}</h1>
                        </div>
                        <div class="md:text-end mt-4 md:mt-0">
                            <div class="flex items-center gap-3 text-gray-800">
                                <img src="{{ asset('images/stopwatch.svg') }}" alt="">
                                <h1>Terakhir Online</h1>
                            </div>
                            {{ \Carbon\Carbon::parse($user->users_logs->last_login)->translatedFormat('d F Y') }}
                            <h1>{{ $user->users_logs->last_location }}</h1>
                        </div>
                    </div>
                </div>

                <!-- notifikasi -->

                @foreach($logs as $log)

                <a href="{{ route('monitoring.' . $log->device->virdi_type, ['serial_number' => $log->device->serial_number]) }}" class="group">
                    <div class="flex justify-between items-center mx-5">
                        <div class="flex items-center gap-4">
                            <x-dynamic-component
                                :component="'icon.' . ($iconMap[$log->device->virdi_type])"
                                :boxed=true />
                            <div>
                                <h1 class="text-xl group-hover:underline">{{ $log->device->name }}</h1>
                                <p class="text-base text-gray-400 hidden lg:block">{{ $log->activity }}</p>
                            </div>
                        </div>
                        <div class="text-right text-base text-gray-400 hidden lg:block">
                            <!-- <p>Baru Saja</p> -->
                            <p>{{ $log->created_at->format('H:i:s') }}</p>
                        </div>
                    </div>
                    <p class="text-base text-gray-400 mt-2 mx-5 lg:hidden">{{ $log->activity }}</p>
                    <div class="flex justify-between text-right text-base text-gray-400 mx-5 lg:hidden">
                        <!-- <p>Baru Saja</p> -->
                        <p>{{ $log->created_at->format('H:i:s') }}</p>
                    </div>
                </a>

                <hr class="my-5 text-gray-400">

                @endforeach
            </section>

            <section class="lg:w-7/12 mt-7 lg:mt-0">
                <!-- Section Alat -->
                <section class="flex-auto bg-[#FFFFF0] rounded-3xl p-11 mb-7 text-xl text-gray-400">
                    <div class="flex justify-between items-center mb-8 text-2xl text-gray-800">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/cpu.svg') }}" alt="">
                            <h1>Alat anda</h1>
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="button" id="btn-tambah-alat"
                                class="hidden lg:block p-2 bg-[#D1D1C6] rounded-full cursor-pointer hover:bg-[#b8b8ae]">
                                <img src="{{ asset('images/add.svg') }}" alt="">
                            </button>
                            <button type="button" id="btn-edit-alat"
                                class="p-2 bg-[#D1D1C6] rounded-full cursor-pointer hover:bg-[#b8b8ae]">
                                <img src="{{ asset('images/setting.svg') }}" alt="">
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="overflow-x-auto overflow-y-auto">
                            <div class="min-w-[600px] lg:min-w-0 h-60">
                                <div class="top-0 sticky bg-[#FFFFF0]">
                                    <div class="grid grid-cols-3 items-center text-gray-800">
                                        <div class="justify-self-start ml-4">Alat Anda</div>
                                        <div class="justify-self-center">Kategori</div>
                                        <div class="justify-self-end mr-4">Status</div>
                                    </div>
                                    <hr class="my-4">
                                </div>

                                @foreach($user->devices as $device)
                                <a href="{{ route('monitoring.' . $device->virdi_type, ['serial_number' => $device->serial_number]) }}" class="group">
                                    <div class="grid grid-cols-3 items-center ml-4">
                                        <div class="justify-self-start flex items-center">
                                            <x-dynamic-component
                                                :component="'icon.' . ($iconMap[$device->virdi_type])"
                                                :boxed=true />
                                            <div>
                                                <h1 class="text-gray-800 group-hover:underline">{{ $device->name }}</h1>
                                                <p class="text-lg">ID {{ $device->serial_number }}</p>
                                            </div>
                                        </div>
                                        <div class="justify-self-center  text-lg">Virdi - {{ $device->virdi_type }}</div>
                                        <div class="justify-self-end mr-4 text-[16px]">
                                            <x-icon.online-status :status="$device->status" />
                                        </div>
                                    </div>
                                </a>

                                <hr class="my-4">
                                @endforeach
                            </div>
                        </div>
                    </div>

                </section>


                <!-- Section Data Stream -->
                <section class="flex-auto bg-[#FFFFF0] rounded-3xl p-11">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/chart.svg') }}" alt="">
                            <h1>Data Stream</h1>
                        </div>
                        <h1 class="hidden lg:block">
                            14 Kilo Byte
                        </h1>
                    </div>
                    <div class="relative lg:h-[380px] h-[360px] mt-3">
                        <canvas id="myChart"></canvas>
                    </div>
                </section>
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
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                    label: 'Feed - 1',
                    data: [15, 25, 35, 30, 15, 20, 10, 25, 15, 18, 17, 20],
                    backgroundColor: '#D1BE4F',
                    stack: 'combined',
                    borderRadius: 8,
                    barPercentage: 0.5,
                    categoryPercentage: 0.6
                },
                {
                    label: 'Humida - 1',
                    data: [10, 15, 25, 20, 10, 15, 5, 20, 10, 15, 12, 15],
                    backgroundColor: '#62A19E',
                    stack: 'combined',
                    borderRadius: 8,
                    barPercentage: 0.5,
                    categoryPercentage: 0.6
                },
                {
                    label: 'Siram - 1',
                    data: [12, 18, 30, 25, 8, 18, 6, 30, 12, 18, 15, 22],
                    backgroundColor: '#80B56F',
                    stack: 'combined',
                    borderRadius: 8,
                    barPercentage: 0.5,
                    categoryPercentage: 0.6
                },
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
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Data Stream (Byte)'
                    }
                }
            }
        }
    });
</script>

<script>
    // Sidebar
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebar-close');
    const overlay = document.getElementById('overlay');

    hamburger.addEventListener("click", () => {
        sidebar.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-40');
        sidebar.classList.remove('-translate-x-full');
    })

    sidebarClose.addEventListener("click", () => {
        sidebar.classList.remove('translate-x-0');
        overlay.classList.add('hidden');
        sidebar.classList.add('-translate-x-full');
    })

    overlay.addEventListener("click", () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        formEdit.classList.add('hidden');
        formTambah.classList.add('hidden');
        formProfile.classList.add('hidden');
        overlay.classList.add('opacity-40');
    })


    // form edit alat
    const btnEdit = document.getElementById('btn-edit-alat');
    const formEdit = document.getElementById('form-edit-alat');
    btnEdit.addEventListener("click", () => {
        formEdit.classList.remove('hidden');
        overlay.classList.remove('hidden');
    })


    // form tambah alat
    const btnTambah = document.getElementById('btn-tambah-alat');
    const btnExitFormTambahAlat = document.getElementById('exit-form-tambah-alat')
    const formTambah = document.getElementById('form-tambah-alat');
    btnTambah.addEventListener("click", () => {
        formTambah.classList.remove('hidden');
        overlay.classList.remove('hidden');
    })
    btnExitFormTambahAlat.addEventListener("click", () => {
        formTambah.classList.add('hidden');
        overlay.classList.add('hidden');
    })

    // form edit profile
    const btnProfile = document.getElementById('btn-edit-profile');
    const formProfile = document.getElementById('form-edit-profile');
    btnProfile.addEventListener("click", () => {
        formProfile.classList.remove('hidden');
        overlay.classList.remove('hidden');
    })
</script>

</html>
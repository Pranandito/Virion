@props([
'devices',
'iconMap' => [
'Humida' => 'humida-logo',
'Siram' => 'siram-logo',
'Feed' => 'feed-logo',
],
])


<!-- Side Bar -->
<aside id="sidebar"
    class="fixed transform -translate-x-full left-0 top-0 w-full lg:w-86 h-screen bg-[#FFFFF0] shadow-2xl z-30 text-xl text-[#383838] overflow-y-auto">
    <div class="flex items-center justify-between mb-10 mt-8 mx-9">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/Logo.png') }}" alt="" class="size-14">
            <h1>Virion</h1>
        </div>
        <button id="sidebar-close" type="button" class="cursor-pointer">
            <i class="bi bi-layout-sidebar hover:bg-[#F4F7F3] rounded-full px-3 py-2"></i>
        </button>
    </div>
    <a href="{{ route('dashboard') }}" class="">
        <div class="flex items-center gap-4 p-2 my-2 mx-11 rounded-xl hover:bg-gray-200">
            <img src="{{ asset('images/home.svg') }}" alt="" class="">
            <h1>Beranda</h1>
        </div>
    </a>
    <hr class="my-5 text-gray-300">
    <div class="flex items-center gap-4 px-11">
        <img src="{{ asset('images/dashboard.svg') }}" alt="" class="">
        <h1>Dashboard</h1>
    </div>
    <div class="mx-11 pl-4">
        @foreach($devices as $device)
        <a href="{{ route('monitoring.' . $device->virdi_type, ['serial_number' => $device->serial_number]) }}" class="">
            <div class="flex items-center gap-4 p-2 m-2 rounded-xl hover:bg-gray-200">
                <x-dynamic-component
                    :component="'icon.' . ($iconMap[$device->virdi_type])"
                    :boxed=false />
                <h1>{{ $device->name }}</h1>
            </div>
        </a>
        @endforeach
    </div>
    <hr class="my-5 text-gray-300">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();" class="">
            <div class=" flex items-center gap-4 p-2 my-2 mx-11 rounded-xl hover:bg-gray-200">
                <img src="{{ asset('images/logout.svg') }}" alt="" class="">
                <h1>Logout</h1>
            </div>
        </a>
    </form>
</aside>
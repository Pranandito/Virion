@props(['mode', 'virdi_type', 'id', 'name', 'color'])

<div class="relative inline-block">
    <button id="dropdownRadioHelperButton" data-dropdown-toggle="dropdownRadioHelper"
        class="cursor-pointer text-gray-800 bg-[#FFFFF0] rounded-xl px-5 py-1.5 text-center inline-flex items-center border {{ $color }} hover:text-[#FFFFF0]"
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
            <form action="{{ route('edit-mode') }}" method="POST">
                @csrf
                <li>
                    <div class="flex p-2 rounded-sm hover:bg-gray-100">
                        <div class="flex items-center h-5">
                            <input id="helper-radio-1" type="radio" name="mode" value="Auto" onchange="this.form.submit()" {{ ($mode == "Auto") ? 'checked' : '' }}
                                class="w-4 h-4 text-gray-800 bg-[#FFFFF0] border-gray-300">
                        </div>
                        <div class="ms-2 text-sm">
                            <label for="helper-radio-1" class="font-medium text-gray-900">
                                <div>Automatic</div>
                                <p id="helper-radio-text-1"
                                    class="text-xs font-normal text-gray-500">
                                    Memberi pakan sesuai jadwal secara otomatis
                                </p>
                            </label>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex p-2 rounded-sm hover:bg-gray-100">
                        <div class="flex items-center h-5">
                            <input id="helper-radio-2" type="radio" name="mode" value="On" onchange="this.form.submit()" {{ ($mode == "On") ? 'checked' : '' }}
                                class="w-4 h-4 text-gray-800 bg-[#FFFFF0] border-gray-300">
                        </div>
                        <div class="ms-2 text-sm">
                            <label for="helper-radio-2" class="font-medium text-gray-900">
                                <div>Manual - On</div>
                                <p id="helper-radio-text-2"
                                    class="text-xs font-normal text-gray-500">
                                    Memberi pakan sekarang
                                </p>
                            </label>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex p-2 rounded-sm hover:bg-gray-100">
                        <div class="flex items-center h-5">
                            <input id="helper-radio-3" type="radio" name="mode" value="Off" onchange="this.form.submit()" {{ ($mode == "Off") ? 'checked' : '' }}
                                class="w-4 h-4 text-gray-800 bg-[#FFFFF0] border-gray-300">
                        </div>
                        <div class="ms-2 text-sm">
                            <label for="helper-radio-3" class="font-medium text-gray-900">
                                <div>Manual - Off</div>
                                <p id="helper-radio-text-3"
                                    class="text-xs font-normal text-gray-500">
                                    Mematikan perangkat
                                </p>
                            </label>
                        </div>
                    </div>
                </li>
                <input type="hidden" name="virdi_type" value="{{ $virdi_type }}">
                <input type="hidden" name="device_id" value="{{ $id }}">
                <input type="hidden" name="name" value="{{ $name }}">
            </form>
        </ul>
    </div>
</div>
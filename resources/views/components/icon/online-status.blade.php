@props(['status' => 0])

@if($status == 0)
<h1 class="px-3 py-[3px] rounded-full text-[#FFFFF0] bg-[#AEB4B7]">
    Offline
</h1>
@else
<h1 class="px-3 py-[3px] rounded-full text-[#FFFFF0] bg-[#4CAF50]">
    Online
</h1>
@endif
@php($route = Route::currentRouteName())
<div class="w-[15%] flex flex-col h-screen sticky bg-dark-400  drop-shadow-xl">
    <div class="text-xl text-center p-3 font-bold bg-dark-500">Council-Helper</div>

    <a
        class="pl-10 p-5 text-lg [&[active]]:bg-white/10 hover:bg-white/10"
        href="{{ route('admin.dashboard') }}"
        @if ($route === 'admin.dashboard') active @endif
    >
        <i class="fas fa-gauge mr-2"></i> Dashboard
    </a>

    <a
        class="pl-10 p-5 text-lg [&[active]]:bg-white/10 hover:bg-white/10"
        href="{{ route('admin.teams') }}"
        @if ($route === 'admin.teams') active @endif
    >
        <i class="fas fa-people-group mr-2"></i> Teams
    </a>

    <form method="post" action="{{ route('admin.logout') }}" class="mt-auto">
        @csrf
        <button class="w-full p-5 text-center text-red-500 text-lg hover:bg-red-200/10">
            <i class="fas fa-arrow-right-from-bracket fa-rotate-180 mr-2"></i> Logout
        </button>
    </form>
</div>

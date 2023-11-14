<div class="w-[15%] flex flex-col h-screen sticky bg-dark-400  drop-shadow-xl">
    <div class="text-xl text-center p-3 font-bold bg-dark-500">Council-Helper</div>

    <a class="pl-10 p-5 text-lg hover:bg-white/10">
        <i class="fas fa-people-group mr-2"></i> Kader
    </a>

    <form method="post" action="{{ route('admin.logout') }}" class="mt-auto">
        @csrf
        <button class="w-full p-5 text-center text-red-500 text-lg hover:bg-red-200/10">
            <i class="fas fa-arrow-right-from-bracket fa-rotate-180 mr-2"></i> Logout
        </button>
    </form>
</div>

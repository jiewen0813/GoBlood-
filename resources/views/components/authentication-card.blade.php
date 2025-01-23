<div class="min-h-screen flex">
    <div class="w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('img/card_background.png') }}'); background-size: cover; background-position: center;"></div>

    <div class="w-1/2 flex flex-col justify-center items-center bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-center mb-6">
                {{ $logo }}
            </div>

            <!-- Authentication form -->
            {{ $slot }}
        </div>
    </div>
</div>

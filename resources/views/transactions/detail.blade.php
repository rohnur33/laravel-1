<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi &raquo;{{ $item->food->name }} by {{ $item->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full md:w-1/6 px-4 mb-4 md:mb-8">
                <img src="{{ $item->food->picturespath }}" alt="" class="w-full rounded">
            </div>
            <div class="w-full md:w5/6 px-4 mb-4 md:mb-0">
                <div class="flex flex-warp mb-3">
                    <div class="w-2/6">
                        <div class="text-sm">Product Name</div>
                        <div class="text-xl font-bold">{{ $item->food->name }}</div>
                    </div>
                    <div class="w-1/6">
                        <div class="text-sm">Quantity</div>
                        <div class="text-xl font-bold">{{ number_format($item->qyt) }}</div>
                    </div>
                    <div class="w-1/6">
                        <div class="text-sm">Total</div>
                        <div class="text-xl font-bold">{{ number_format($item->total) }}</div>
                    </div>
                    <div class="w-1/6">
                        <div class="text-sm">Status</div>
                        <div class="text-xl font-bold">{{ $item->status }}</div>
                    </div>
                    
                </div>
                <div class="flex flex-warp mb-3">
                    <div class="w-2/6">
                        <div class="text-sm">User Name</div>
                        <div class="text-xl font-bold">{{ $item->user->name }}</div>
                    </div>
                    <div class="w-3/6">
                        <div class="text-sm">Email</div>
                        <div class="text-xl font-bold">{{ $item->user->email }}</div>
                    </div>                    
                    <div class="w-1/6">
                        <div class="text-sm">City</div>
                        <div class="text-xl font-bold">{{ $item->user->city }}</div>
                    </div>
                    
                </div>
                <div class="flex flex-warp mb-3">
                    <div class="w-4/6">
                        <div class="text-sm">Address</div>
                        <div class="text-xl font-bold">{{ $item->user->address }}</div>
                    </div>
                    <div class="w-1/6">
                        <div class="text-sm">Number</div>
                        <div class="text-xl font-bold">{{ $item->user->housenumber }}</div>
                    </div>                    
                    <div class="w-1/6">
                        <div class="text-sm">User Phone</div>
                        <div class="text-xl font-bold">{{ $item->user->phonenumber }}</div>
                    </div>
                    
                </div>
                <div class="flex flex-warp mb-3">
                    <div class="w-5/6">
                        <div class="text-sm">Payment URL</div>
                        <div class="text-lg">
                            <a href="{{ $item->paymemnt_url }}">{{ $item->paymemnt_url }}</a>
                        </div>
                    </div>
                    <div class="w-1/6">
                        <div class="text-sm mb-1">Change Status</div>
                        <a href="#"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold px-2 rounded block text-center w-full mb-1">
                            On Delivery
                        </a>
                        <a href="#" 
                            class="bg-green-500 hover:bg-green-700 text-black font-bold px-2 rounded block text-center w-full mb-1">
                            Delivered
                        </a>
                        <a href="#"
                            class="bg-green-500 hover:bg-green-700 text-black font-bold px-2 rounded block text-center w-full mb-1">
                            Cencelled
                        </a>
                    </div>                    
                </div>
            </div>
        </div> 
    </div>
</x-app-layout>

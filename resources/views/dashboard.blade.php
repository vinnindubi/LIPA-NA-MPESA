<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action={{route('mpesa')}} method="POST">
                        @csrf
                        <div class="flex flex-column full-width">
                            <label for="PhoneNumber">Phone:</label>
                        <input type="text" placeholder="0712345678" name="PhoneNumber" class="mb-3"/>
                        </div>
                        <div class="flex flex-column full-width">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" placeholder=""/>
                        </div>
                        <button type="submit">Pay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

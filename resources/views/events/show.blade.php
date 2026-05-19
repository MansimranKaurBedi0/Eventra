<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->name }} Dashboard
            </h2>
            <div class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} • {{ $event->location }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Budget</div>
                    <div class="text-2xl font-bold mt-1">₹{{ number_format($event->budget) }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Expected Guests</div>
                    <div class="text-2xl font-bold mt-1">{{ $event->guest_count }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Vendors Booked</div>
                    <div class="text-2xl font-bold mt-1">0</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Days Left</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ max(0, \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($event->date))) }}
                    </div>
                </div>
            </div>

            <!-- Main Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Guest List Management -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Guest List</h3>
                        <span class="text-sm bg-blue-100 text-blue-800 py-1 px-2 rounded-full">{{ $event->guests->count() }} / {{ $event->guest_count }} Guests</span>
                    </div>

                    <!-- Add Guest Form -->
                    <form action="{{ route('guests.store', $event) }}" method="POST" class="flex flex-col gap-2 mb-4">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="name" placeholder="Guest Name" required class="w-1/2 border-gray-300 rounded-md shadow-sm text-sm">
                            <input type="email" name="email" placeholder="Email Address" required class="w-1/2 border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <div class="flex gap-2">
                            <select name="rsvp_status" class="w-2/3 border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="pending">Pending</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <button type="submit" class="w-1/3 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Add & Invite</button>
                        </div>
                    </form>

                    <!-- Guest List -->
                    <ul class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                        @forelse($event->guests as $guest)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $guest->name }}</p>
                                    <p class="text-xs text-gray-500">Status: <span class="uppercase font-bold {{ $guest->rsvp_status === 'yes' ? 'text-green-500' : ($guest->rsvp_status === 'no' ? 'text-red-500' : 'text-yellow-500') }}">{{ $guest->rsvp_status }}</span></p>
                                </div>
                                <form action="{{ route('guests.destroy', $guest) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                </form>
                            </li>
                        @empty
                            <li class="py-4 text-center text-sm text-gray-500">No guests added yet.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Budget Tracker -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Budget Tracker</h3>
                        @php $spent = $event->expenses->sum('amount'); @endphp
                        <span class="text-sm {{ $spent > $event->budget ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} py-1 px-2 rounded-full">Spent: ₹{{ number_format($spent) }} / ₹{{ number_format($event->budget) }}</span>
                    </div>

                    <!-- Add Expense Form -->
                    <form action="{{ route('expenses.store', $event) }}" method="POST" class="flex gap-2 mb-4">
                        @csrf
                        <input type="text" name="title" placeholder="Expense (e.g. Catering)" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <input type="number" name="amount" placeholder="₹ Amount" required class="w-32 border-gray-300 rounded-md shadow-sm text-sm">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Add</button>
                    </form>

                    <!-- Expense List -->
                    <ul class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                        @forelse($event->expenses as $expense)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $expense->title }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm text-gray-600 font-bold">₹{{ number_format($expense->amount) }}</p>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remove</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-sm text-gray-500">No expenses logged yet.</li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Vendor Management link (temp) -->
                <div class="col-span-full bg-white rounded-lg shadow-sm p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold">Vendors</h3>
                        <p class="text-sm text-gray-500">Find and book vendors for your event.</p>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition">Search Vendors &rarr;</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

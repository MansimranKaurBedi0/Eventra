<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendor Search') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('vendors.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-1/3">
                        <x-input-label for="category" :value="__('Category')" />
                        <select name="category" id="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            <option value="Catering" {{ request('category') == 'Catering' ? 'selected' : '' }}>Catering</option>
                            <option value="Photography" {{ request('category') == 'Photography' ? 'selected' : '' }}>Photography</option>
                            <option value="Decoration" {{ request('category') == 'Decoration' ? 'selected' : '' }}>Decoration</option>
                            <option value="Music" {{ request('category') == 'Music' ? 'selected' : '' }}>Music</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:w-1/3">
                        <x-input-label for="location" :value="__('Location')" />
                        <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="request('location')" placeholder="City or area" />
                    </div>

                    <div class="w-full md:w-1/3">
                        <x-input-label for="max_price" :value="__('Max Starting Price (₹)')" />
                        <x-text-input id="max_price" class="block mt-1 w-full" type="number" name="max_price" :value="request('max_price')" placeholder="e.g. 50000" />
                    </div>

                    <div class="w-full md:w-auto flex gap-2">
                        <x-primary-button type="submit">{{ __('Search') }}</x-primary-button>
                        <a href="{{ route('vendors.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Results -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($vendors as $vendor)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="h-32 bg-gray-200 w-full object-cover">
                            <!-- Placeholder for vendor image -->
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-900">{{ $vendor->company_name }}</h3>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $vendor->category }}</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p>📍 {{ $vendor->location }}</p>
                                <p>⭐ {{ $vendor->rating }} / 5.0</p>
                                <p>💰 Starts at ₹{{ number_format($vendor->starting_price) }}</p>
                            </div>
                            
                            @if(Auth::user()->role === 'planner')
                                @if(Auth::user()->events->count() > 0)
                                    <form action="{{ route('bookings.store') }}" method="POST" class="mt-4 border-t pt-4">
                                        @csrf
                                        <input type="hidden" name="vendor_profile_id" value="{{ $vendor->id }}">
                                        <input type="hidden" name="agreed_price" value="{{ $vendor->starting_price }}">
                                        <div class="flex items-center gap-2">
                                            <select name="event_id" required class="flex-1 border-gray-300 text-sm rounded">
                                                <option value="">Select Event...</option>
                                                @foreach(Auth::user()->events as $event)
                                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="flex-shrink-0 bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700 shadow-sm font-bold">Book</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="mt-4 border-t pt-4 text-sm text-red-500 font-semibold text-center">
                                        Create an event first to book vendors!
                                    </div>
                                @endif
                            @else
                                <button class="mt-4 w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 rounded text-sm hover:bg-gray-100 font-semibold transition">View Profile</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-6 text-center text-gray-500 shadow-sm sm:rounded-lg">
                        No vendors found matching your criteria.
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $vendors->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

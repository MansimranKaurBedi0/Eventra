<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(Auth::user()->role === 'planner')
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">My Events</h3>
                    <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create New Event
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse(Auth::user()->events as $event)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 hover:shadow-md transition">
                            <h4 class="font-bold text-xl mb-2">{{ $event->name }}</h4>
                            <p class="text-gray-600 text-sm mb-4"><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</p>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>📍 {{ $event->location }}</p>
                                <p>👥 {{ $event->guest_count }} Guests</p>
                                <p>💰 ₹{{ number_format($event->budget, 2) }}</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 text-right">
                                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Manage Event &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white p-6 text-center text-gray-500 shadow-sm sm:rounded-lg">
                            You haven't created any events yet. Click the button above to get started!
                        </div>
                    @endforelse
                </div>
            @elseif(Auth::user()->role === 'vendor')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">My Vendor Profile</h3>
                    <p class="text-gray-600 mb-6">Fill out your profile details so planners can find you in the search!</p>

                    <form action="{{ route('vendors.profile.store') }}" method="POST" class="max-w-xl space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="company_name" :value="__('Company Name')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="Auth::user()->vendorProfile->company_name ?? ''" required />
                        </div>
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="Catering" {{ (Auth::user()->vendorProfile->category ?? '') == 'Catering' ? 'selected' : '' }}>Catering</option>
                                <option value="Photography" {{ (Auth::user()->vendorProfile->category ?? '') == 'Photography' ? 'selected' : '' }}>Photography</option>
                                <option value="Decoration" {{ (Auth::user()->vendorProfile->category ?? '') == 'Decoration' ? 'selected' : '' }}>Decoration</option>
                                <option value="Music" {{ (Auth::user()->vendorProfile->category ?? '') == 'Music' ? 'selected' : '' }}>Music</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="Auth::user()->vendorProfile->location ?? ''" required />
                        </div>
                        <div>
                            <x-input-label for="starting_price" :value="__('Starting Price (₹)')" />
                            <x-text-input id="starting_price" class="block mt-1 w-full" type="number" name="starting_price" :value="Auth::user()->vendorProfile->starting_price ?? ''" />
                        </div>
                        
                        <x-primary-button>Save Profile</x-primary-button>
                    </form>
                </div>

                <!-- Incoming Bookings -->
                @if(Auth::user()->vendorProfile)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Incoming Bookings</h3>
                        
                        <ul class="divide-y divide-gray-100">
                            @forelse(Auth::user()->vendorProfile->bookings as $booking)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold">{{ $booking->event->name }}</p>
                                        <p class="text-sm text-gray-500">Planner: {{ $booking->event->user->name }}</p>
                                        <p class="text-sm text-gray-500">Date: {{ $booking->event->date }}</p>
                                        <p class="text-sm font-semibold text-indigo-600">Price: ₹{{ number_format($booking->agreed_price) }}</p>
                                        <p class="text-xs font-bold uppercase mt-1 {{ $booking->status === 'approved' ? 'text-green-500' : ($booking->status === 'rejected' ? 'text-red-500' : 'text-yellow-500') }}">{{ $booking->status }}</p>
                                    </div>
                                    
                                    @if($booking->status === 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('bookings.update', $booking) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="bg-green-500 text-white px-3 py-1 text-sm rounded hover:bg-green-600">Approve</button>
                                            </form>
                                            <form action="{{ route('bookings.update', $booking) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 text-sm rounded hover:bg-red-600">Reject</button>
                                            </form>
                                        </div>
                                    @endif
                                </li>
                            @empty
                                <li class="py-4 text-center text-gray-500">No bookings yet.</li>
                            @endforelse
                        </ul>
                    </div>
                @endif
            @elseif(Auth::user()->role === 'guest')
                @php
                    $guestInvitations = \App\Models\Guest::where('email', Auth::user()->email)->with('event')->get();
                @endphp
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">My Invitations</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($guestInvitations as $invitation)
                            <div class="border rounded-lg p-6 hover:shadow-md transition">
                                <h4 class="font-bold text-xl mb-2">{{ $invitation->event->name }}</h4>
                                <div class="text-sm text-gray-600 space-y-1 mb-4">
                                    <p>📅 {{ \Carbon\Carbon::parse($invitation->event->date)->format('M d, Y') }}</p>
                                    <p>📍 {{ $invitation->event->location }}</p>
                                    <p>👤 Hosted by: {{ $invitation->event->user->name }}</p>
                                </div>
                                
                                <form action="{{ route('guests.rsvp', $invitation) }}" method="POST" class="border-t pt-4 space-y-3">
                                    @csrf @method('PATCH')
                                    <div>
                                        <x-input-label for="rsvp_status" :value="__('RSVP Status')" />
                                        <select name="rsvp_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="pending" {{ $invitation->rsvp_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="yes" {{ $invitation->rsvp_status === 'yes' ? 'selected' : '' }}>Attending (Yes)</option>
                                            <option value="no" {{ $invitation->rsvp_status === 'no' ? 'selected' : '' }}>Not Attending (No)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="dietary_preferences" :value="__('Dietary Preferences (Optional)')" />
                                        <x-text-input id="dietary_preferences" class="block mt-1 w-full" type="text" name="dietary_preferences" :value="$invitation->dietary_preferences ?? ''" placeholder="e.g. Vegan, Nut Allergy" />
                                    </div>
                                    <x-primary-button>Update RSVP</x-primary-button>
                                </form>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                You don't have any event invitations yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

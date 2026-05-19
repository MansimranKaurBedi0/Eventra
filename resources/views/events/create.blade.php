<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Event Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="date" :value="__('Event Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Location (Venue or City)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="guest_count" :value="__('Expected Guest Count')" />
                            <x-text-input id="guest_count" class="block mt-1 w-full" type="number" name="guest_count" :value="old('guest_count')" required min="1" />
                            <x-input-error :messages="$errors->get('guest_count')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="theme" :value="__('Event Theme (Optional)')" />
                            <x-text-input id="theme" class="block mt-1 w-full" type="text" name="theme" :value="old('theme')" />
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="budget" :value="__('Total Budget (₹)')" />
                            <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01" name="budget" :value="old('budget', 0)" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

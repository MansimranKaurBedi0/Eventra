<x-mail::message>
# You're Invited! 🎉

Hi **{{ $guest->name }}**,

You have been invited to **{{ $event->name }}** by {{ $event->user->name }}.

**Date:** {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}  
**Location:** {{ $event->location }}

We would love to have you there! Please log in to Eventra to RSVP and let us know your dietary preferences.

<x-mail::button :url="url('/login')">
RSVP Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

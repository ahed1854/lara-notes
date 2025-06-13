@component('mail::message')
# Hi there! 👋

This is a friendly reminder for your task: **{{ $reminderTitle }}**

**Description:**
{{ $reminderDescription }}

**Due Date:** {{ \Carbon\Carbon::parse($reminderDueDate)->format('F j, Y, g:i a') }}

Don't forget to complete it!

Thanks,
{{ config('app.name') }}
@endcomponent
@component('mail::message')
# Test Email to Active Users from Computan Test
Hi {{ $user->name }}!

This is a test email to all the active users at {{ date('Y-m-d H:i:s') }}

Thank you!

Computan Test Fahad
@endcomponent

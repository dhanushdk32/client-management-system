Password Changed Successfully - Client Management System

Hello {{ $user->name }},

This email is to confirm that the password for your Client Portal account ({{ $user->email }}) was successfully changed on {{ $changedAt }}.

Portal Login: {{ route('client.login') }}

If you did NOT authorize this change, please contact our support team immediately or reset your password.

Thank you,
Client Management Team

Password Changed Successfully - {{ \App\Models\SystemSetting::get('company_name', 'RORIRI Software Solutions') }}

Hello {{ $user->name }},

This email is to confirm that the password for your account ({{ $user->email }}) was successfully changed on {{ $changedAt }} (IST).

Login Page: {{ route('login') }}

If you did NOT authorize this change, please contact our support team immediately or reset your password.

Thank you,
{{ \App\Models\SystemSetting::get('company_name', 'RORIRI Software Solutions') }}

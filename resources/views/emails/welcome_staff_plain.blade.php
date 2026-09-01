Welcome to the Team - Staff Portal

Hello {{ $staff->name }},

Your Staff Portal account has been provisioned.

Staff Login URL: {{ route('staff.login') }}
Login Email: {{ $staff->email }}
Password: {{ $plainPassword }}

Please log in to manage your assigned clients and support requests.

Thank you,
IT Management Team

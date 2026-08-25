Welcome to Client Management System, {{ $client->client_company }}!

Hello {{ $client->client_name }},

Your client portal account has been created.

Your Login Credentials:
----------------------------------------
Portal URL: {{ route('client.login') }}
Login Email: {{ $client->client_email }}
Password: {{ $plainPassword }}
----------------------------------------

You can log in to view your services, upload documents, and submit support requests.
For security purposes, we recommend updating your password in your Profile Settings after your first login.

Thank you,
Client Management Team

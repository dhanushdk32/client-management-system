Welcome to IT Portal - Account Activation

Hello {{ $name }},

An account has been created for you as a {{ $accountType }} on the Client Management System.

Your 6-Digit Activation OTP Code is:
----------------------------------------
{{ $otpCode }}
----------------------------------------
(This code is valid for 15 minutes)

To activate your account and set your password, visit:
{{ route('account.activate', ['email' => $email]) }}

Thank you,
IT Management Team

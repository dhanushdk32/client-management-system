<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Notification</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 30px 15px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); padding: 35px 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">Password Changed Successfully</h1>
            <p style="margin: 8px 0 0 0; font-size: 14px; color: #e0f2fe; opacity: 0.95;">Security Notification for Your Account</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 35px 30px;">
            <p style="color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;">
                Hello <strong>{{ $user->name }}</strong>,
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                This email is to confirm that the password for your account (<strong>{{ $user->email }}</strong>) was successfully updated on <strong>{{ $changedAt }} (IST)</strong>.
            </p>

            <!-- Status Card -->
            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-left: 5px solid #22c55e; border-radius: 8px; padding: 18px 20px; margin: 25px 0;">
                <h4 style="margin: 0 0 8px 0; color: #166534; font-size: 14px; font-weight: 700;">
                    ✓ Status: Password Updated
                </h4>
                <p style="margin: 0; color: #15803d; font-size: 13px; line-height: 1.5;">
                    Your account is secure with your new password. You can now use it to log in to the portal.
                </p>
            </div>

            <!-- Login Action Button -->
            <div style="text-align: center; margin: 30px 0 25px 0;">
                <a href="{{ route('login') }}" style="display: inline-block; background-color: #0284c7; color: #ffffff; padding: 13px 32px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                    Go to Login Page
                </a>
            </div>

            <!-- Security Warning -->
            <div style="background-color: #fef2f2; border-radius: 8px; padding: 14px 18px; margin-top: 25px; border: 1px solid #fecaca;">
                <p style="margin: 0; color: #991b1b; font-size: 13px; line-height: 1.5;">
                    <strong>Didn't make this change?</strong> If you did not perform this password change, please contact our support immediately or reset your password to protect your account.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                &copy; {{ date('Y') }} {{ \App\Models\SystemSetting::get('company_name', 'RORIRI Software Solutions') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

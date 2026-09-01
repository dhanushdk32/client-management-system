<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated Successfully</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 30px 15px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 35px 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">Account Activated!</h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #d1fae5; opacity: 0.95;">Your password has been successfully configured.</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 35px 30px;">
            <p style="color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;">
                Hello <strong>{{ $name }}</strong>,
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                Your <strong>{{ $accountType }}</strong> account has been verified and activated. You can now use your email and newly created password to log in.
            </p>

            <!-- Account Summary Card -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 5px solid #10b981; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 120px;"><strong>Login URL:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">
                            <a href="{{ $loginUrl }}" style="color: #059669; text-decoration: none; font-weight: 600;">{{ $loginUrl }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Username/Email:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Status:</strong></td>
                        <td style="padding: 6px 0; color: #059669; font-weight: 700;">✓ Active</td>
                    </tr>
                </table>
            </div>

            <!-- Login Action Button -->
            <div style="text-align: center; margin: 35px 0 25px 0;">
                <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #059669; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                    Sign In to Your Portal
                </a>
            </div>

            <!-- Security Tip -->
            <div style="background-color: #f0fdf4; border-radius: 8px; padding: 14px 18px; margin-top: 25px; border: 1px solid #bbf7d0;">
                <p style="margin: 0; color: #166534; font-size: 13px; line-height: 1.5;">
                    💡 <strong>Tip:</strong> Keep your login credentials safe. You can update your profile details and preferences anytime inside your portal settings.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                &copy; {{ date('Y') }} Client Management System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

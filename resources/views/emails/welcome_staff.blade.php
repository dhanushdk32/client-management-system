<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Team</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 30px 15px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); padding: 35px 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">Welcome to the Team!</h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #c7d2fe; opacity: 0.95;">Your Staff Portal account has been provisioned.</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 35px 30px;">
            <p style="color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;">
                Hello <strong>{{ $staff->name }}</strong>,
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                You have been onboarded as a <strong>{{ $staff->designation }}</strong> in the <strong>{{ $staff->department }}</strong> department.
            </p>

            <!-- Credentials Box -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 5px solid #4f46e5; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <h4 style="margin: 0 0 15px 0; color: #1e293b; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                    Your Staff Login Credentials
                </h4>
                
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 120px;"><strong>Staff URL:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">
                            <a href="{{ route('staff.login') }}" style="color: #4f46e5; text-decoration: none; font-weight: 600;">{{ route('staff.login') }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Email:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">{{ $staff->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Password:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">
                            <span style="background-color: #e2e8f0; padding: 4px 10px; border-radius: 4px; font-family: Consolas, Monaco, monospace; font-size: 15px; font-weight: 700; color: #0f172a;">{{ $plainPassword }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Login Action Button -->
            <div style="text-align: center; margin: 35px 0 25px 0;">
                <a href="{{ route('staff.login') }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                    Sign In to Staff Console
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                &copy; {{ date('Y') }} IT Company Management System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

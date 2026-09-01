<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome & Account Activation</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 30px 15px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 35px 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">Welcome to IT Operations Portal</h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #dbeafe; opacity: 0.95;">Account Activation & Security Setup</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 35px 30px;">
            <p style="color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;">
                Hello <strong>{{ $name }}</strong>,
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                An account has been created for you as a <strong>{{ $accountType }}</strong>{{ $companyOrRole ? ' ('.$companyOrRole.')' : '' }} on the Client Management System.
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                To complete your setup and create your password, please use the 6-digit activation code below:
            </p>

            <!-- OTP Code Box -->
            <div style="background-color: #f8fafc; border: 2px dashed #3b82f6; border-radius: 12px; padding: 25px; text-align: center; margin: 30px 0;">
                <span style="display: block; font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Your One-Time Activation Code</span>
                <span style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #1e40af; font-family: monospace;">{{ $otpCode }}</span>
                <span style="display: block; font-size: 12px; color: #94a3b8; margin-top: 8px;">Valid for 15 minutes</span>
            </div>

            <!-- Activation Action Button -->
            <div style="text-align: center; margin: 35px 0 25px 0;">
                <a href="{{ route('account.activate', ['email' => $email]) }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                    Activate Account & Create Password
                </a>
            </div>

            <!-- Information Tip -->
            <div style="background-color: #eff6ff; border-radius: 8px; padding: 14px 18px; margin-top: 25px; border: 1px solid #dbeafe;">
                <p style="margin: 0; color: #1e40af; font-size: 13px; line-height: 1.5;">
                    <strong>Security Notice:</strong> Do not share this code with anyone. Once you verify your OTP and set your password, your account will be activated immediately.
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

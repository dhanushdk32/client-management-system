<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification Code</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 30px 15px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%); padding: 35px 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">RORIRI Management Portal</h1>
            <p style="margin: 8px 0 0 0; font-size: 15px; color: #e0f2fe; opacity: 0.95;">Account Verification Code</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 35px 30px;">
            <p style="color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;">
                Hello <strong>{{ $name }}</strong>,
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                An account is being provisioned for you as <strong>{{ $accountType }}</strong>{{ $companyOrRole ? ' ('.$companyOrRole.')' : '' }} on the RORIRI Portal.
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.6;">
                Please provide the 6-digit verification OTP code below to complete your account setup:
            </p>

            <!-- OTP Code Box -->
            <div style="background-color: #f8fafc; border: 2px dashed #0284c7; border-radius: 12px; padding: 25px; text-align: center; margin: 30px 0;">
                <span style="display: block; font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Your 6-Digit Verification Code</span>
                <span style="font-size: 38px; font-weight: 800; letter-spacing: 8px; color: #0284c7; font-family: monospace;">{{ $otpCode }}</span>
                <span style="display: block; font-size: 13px; color: #dc2626; font-weight: 600; margin-top: 10px;">⏰ Valid for 5 minutes only</span>
            </div>

            <!-- Security Tip -->
            <div style="background-color: #f0f9ff; border-radius: 8px; padding: 14px 18px; margin-top: 25px; border: 1px solid #bae6fd;">
                <p style="margin: 0; color: #0369a1; font-size: 13px; line-height: 1.5;">
                    <strong>Security Notice:</strong> Do not share this OTP code with unauthorized persons. Once verified, your account password will be set and login credentials will be confirmed.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                &copy; {{ date('Y') }} RORIRI Management System. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

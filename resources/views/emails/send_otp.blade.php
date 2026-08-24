<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
        <h2 style="color: #2b3a8c; margin-top: 0;">Password Reset Request</h2>
        <p style="color: #555; font-size: 16px; line-height: 1.5;">You have requested to reset your password for the Client Management System. Please use the following One-Time Password (OTP) to proceed. This code is valid for 10 minutes.</p>
        
        <div style="background-color: #f8f9fa; border: 2px dashed #3b82f6; border-radius: 8px; padding: 20px; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; color: #1e293b; letter-spacing: 5px;">{{ $otpCode }}</span>
        </div>
        
        <p style="color: #888; font-size: 14px;">If you did not request a password reset, please ignore this email or contact support.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="color: #aaa; font-size: 12px; margin-bottom: 0;">&copy; {{ date('Y') }} Client Management System. All rights reserved.</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the System</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6fb; margin: 0; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #2b3a8c; margin-top: 0; text-align: center;">Welcome, {{ $client->client_company }}!</h2>
        <p style="color: #555; font-size: 16px; line-height: 1.5;">Your account has been successfully created in the Client Management System. You can now log in to view your dashboard, upload documents, and manage support tickets.</p>
        
        <h4 style="color: #1e293b; margin-top: 30px; margin-bottom: 10px;">Your Login Credentials</h4>
        <div style="background-color: #f8f9fa; border-left: 4px solid #3b82f6; padding: 15px; margin-bottom: 30px;">
            <p style="margin: 5px 0; color: #333;"><strong>Email:</strong> {{ $client->client_email }}</p>
            <p style="margin: 5px 0; color: #333;"><strong>Password:</strong> {{ $plainPassword }}</p>
        </div>
        
        <div style="text-align: center; margin: 40px 0;">
            <a href="{{ route('login') }}" style="background-color: #3b82f6; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login to Portal</a>
        </div>
        
        <p style="color: #888; font-size: 14px;">We highly recommend changing your password after your first login.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="color: #aaa; font-size: 12px; margin-bottom: 0; text-align: center;">&copy; {{ date('Y') }} Client Management System. All rights reserved.</p>
    </div>
</body>
</html>

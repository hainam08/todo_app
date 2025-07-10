<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Reminder</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: #405189; padding: 20px; text-align: center;">
                            <h1 style="color: white; margin: 0;">Task Reminder 📌</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333;">Chào {{ $task->user->name }},</h2>
                            <p style="font-size: 16px; color: #555; line-height: 1.6;">
                                Đây là lời nhắc rằng task của bạn có tên:
                                <strong style="color: #000;">"{{ $task->title }}"</strong>
                                sẽ <strong style="color: red;">hết hạn vào ngày {{ $task->due_date->format('d/m/Y') }}</strong>.
                            </p>
                            <p style="font-size: 16px; color: #555; line-height: 1.6;">
                                Hãy đảm bảo rằng bạn hoàn thành task đúng hạn nhé 💪.
                            </p>
                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{ route('user.index') }}" style="background-color: #405189; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Xem Task</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 13px; color: #888;">
                            © {{ date('Y') }} Hai Nam Task System. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

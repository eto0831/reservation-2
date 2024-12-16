<!DOCTYPE html>
<html>
<head>
    <title>{{ $subjectContent }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; line-height: 1.6; color: #333; background-color: #f9f9f9;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9f9f9;">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px;">
                    <tr>
                        <td style="padding: 20px; text-align: left;">
                            <h1 style="color: #007BFF; font-size: 24px; margin: 0; padding: 0;">{{ $subjectContent }}</h1>
                            <p style="font-size: 16px; line-height: 1.5; color: #555; margin-top: 20px;">
                                {!! nl2br(e($messageContent)) !!}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

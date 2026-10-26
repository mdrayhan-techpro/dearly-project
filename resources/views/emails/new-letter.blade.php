<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Sealed Letter Has Arrived</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F4EFE6; font-family: 'Georgia', serif; color: #2E2421; line-height: 1.6;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F4EFE6; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; background-color: #FDFBF7; border: 1px solid #D6C7AE; border-radius: 8px; box-shadow: 0 4px 12px rgba(46, 36, 33, 0.08); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #2E2421; padding: 30px 20px; border-bottom: 3px solid #C29B38;">
                            <div style="font-family: 'Georgia', serif; font-size: 26px; font-weight: bold; color: #FAF6EE; letter-spacing: 2px;">DEARLY</div>
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 3px; color: #C29B38; margin-top: 5px;">Letters Find Their Way</div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 35px;">
                            <h2 style="font-size: 20px; color: #783C46; margin: 0 0 20px 0; font-weight: normal; font-style: italic;">
                                Dearest {{ $recipientUser->profile->nickname ?? 'Friend' }},
                            </h2>
                            <p style="font-size: 15px; color: #4A3E39; margin-bottom: 24px;">
                                A courier has just deposited a hand-crafted, sealed envelope into your private mailbox pigeonhole.
                            </p>

                            <!-- Letter Dispatch Notice -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF6EE; border: 1px dashed #D6C7AE; border-radius: 6px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="vertical-align: top; width: 60px;">
                                                    <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #783C46; color: #FAF6EE; font-size: 20px; font-weight: bold; text-align: center; line-height: 48px; font-family: 'Georgia', serif; border: 2px solid #C29B38;">
                                                        &#9993;
                                                    </div>
                                                </td>
                                                <td style="vertical-align: top; padding-left: 15px;">
                                                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #8A7972;">From Sender</div>
                                                    <div style="font-size: 18px; font-weight: bold; color: #2E2421; margin: 2px 0;">
                                                        {{ $letter->sender->profile->nickname ?? 'A Pen-Pal' }}
                                                    </div>
                                                    <div style="font-size: 13px; color: #783C46; font-style: italic;">
                                                        "{{ $letter->title }}"
                                                    </div>
                                                    <div style="font-size: 11px; color: #8A7972; margin-top: 6px;">
                                                        Theme: {{ $letter->stationeryTheme->name ?? 'Standard' }} &bull; Stamp: {{ $letter->postageStamp->name ?? 'Archival' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #5C4F4A; margin-bottom: 30px;">
                                The wax seal is currently unbroken. When you are ready to savor their thoughts with a cup of tea, visit your mailbox to unfold and read.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto 30px auto;">
                                <tr>
                                    <td align="center" style="background-color: #783C46; border-radius: 4px; box-shadow: 0 2px 6px rgba(120, 60, 70, 0.3);">
                                        <a href="{{ route('letters.show', $letter) }}" target="_blank" style="display: inline-block; padding: 14px 28px; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: none; letter-spacing: 1px;">
                                            Open Letter & Unfold Envelope &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; color: #8A7972; border-top: 1px solid #E0D7D0; padding-top: 20px; margin-bottom: 0;">
                                Warmly,<br>
                                <em>The Dearly Postmaster</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #FAF6EE; padding: 20px; border-top: 1px solid #E0D7D0; font-size: 11px; color: #8A7972;">
                            <p style="margin: 0 0 8px 0;">This notification was delivered to your registered email.</p>
                            <p style="margin: 0;">Dearly &bull; Pure Human Correspondence &bull; Slow, Intentional Connection</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

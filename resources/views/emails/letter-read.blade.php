<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Your Letter Has Been Read') }}</title>
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
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 3px; color: #C29B38; margin-top: 5px;">{{ __('Letters Find Their Way') }}</div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 35px; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                            <h2 style="font-size: 20px; color: #783C46; margin: 0 0 20px 0; font-weight: normal; font-style: italic;">
                                {{ __('Dearest :name,', ['name' => $senderUser->profile->nickname ?? __('Friend')]) }}
                            </h2>
                            <p style="font-size: 15px; color: #4A3E39; margin-bottom: 24px;">
                                {{ __('We are pleased to notify you that the recipient has broken the wax seal and unfolded your correspondence.') }}
                            </p>

                            <!-- Read Receipt Card -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF6EE; border: 1px dashed #D6C7AE; border-radius: 6px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 24px; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #8A7972;">{{ __('Read Receipt Notice') }}</div>
                                        <div style="font-size: 18px; font-weight: bold; color: #2E2421; margin: 4px 0;">
                                            {{ $letter->recipient->profile->nickname ?? __('Your Pen-Pal') }}
                                        </div>
                                        <div style="font-size: 14px; color: #783C46; font-style: italic; margin-top: 4px;">
                                            "{{ $letter->title }}"
                                        </div>
                                        <div style="font-size: 12px; color: #5C4F4A; margin-top: 8px;">
                                            {{ __('Opened at:') }} {{ $letter->read_at ? $letter->read_at->format('M d, Y - h:i A T') : __('Just now') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #5C4F4A; margin-bottom: 30px;">
                                {{ __('True slow-mail encourages thoughtful reflection. Take heart in knowing your words have reached their destination and touched another soul.') }}
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto 30px auto;">
                                <tr>
                                    <td align="center" style="background-color: #783C46; border-radius: 4px; box-shadow: 0 2px 6px rgba(120, 60, 70, 0.3);">
                                        <a href="{{ route('mailbox.sent') }}" target="_blank" style="display: inline-block; padding: 14px 28px; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: none; letter-spacing: 1px;">
                                            {{ __('View Sent Dispatch &rarr;') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; color: #8A7972; border-top: 1px solid #E0D7D0; padding-top: 20px; margin-bottom: 0;">
                                {{ __('Warmly,') }}<br>
                                <em>{{ __('The Dearly Postmaster') }}</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #FAF6EE; padding: 20px; border-top: 1px solid #E0D7D0; font-size: 11px; color: #8A7972;">
                            <p style="margin: 0 0 8px 0;">{{ __('Sent automatically by the Dearly Post Office Delivery Engine.') }}</p>
                            <p style="margin: 0;">{{ __('Dearly • Letters Find Their Way') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

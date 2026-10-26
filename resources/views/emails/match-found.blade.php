<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Kindred Spirit Has Been Discovered</title>
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
                                The Post Office scoring engine has discovered an exceptional resonance in the quiet corners of our catalog. A kindred spirit whose life philosophy, literary tastes, and reflections harmonize closely with yours.
                            </p>

                            <!-- Match Card -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF6EE; border: 1px dashed #D6C7AE; border-radius: 6px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 24px; text-align: center;">
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #8A7972; margin-bottom: 6px;">Compatbility Score</div>
                                        <div style="font-size: 38px; font-weight: bold; color: #783C46; font-family: 'Georgia', serif; line-height: 1;">
                                            {{ round($matchResult->compatibility_score) }}%
                                        </div>
                                        <div style="font-size: 16px; font-weight: bold; color: #2E2421; margin-top: 12px;">
                                            {{ $matchedUser->profile->nickname ?? 'A Kindred Soul' }}
                                        </div>
                                        @if($matchedUser->mailingCode)
                                            <div style="display: inline-block; background-color: #FDFBF7; border: 1px solid #C29B38; padding: 4px 12px; border-radius: 4px; font-family: monospace; font-size: 12px; color: #783C46; margin-top: 8px;">
                                                Postal Code: {{ $matchedUser->mailingCode->code }}
                                            </div>
                                        @endif
                                        @if($matchResult->shared_traits)
                                            <p style="font-size: 13px; color: #5C4F4A; font-style: italic; margin-top: 14px; margin-bottom: 0;">
                                                Key harmonies: {{ implode(', ', array_slice($matchResult->shared_traits, 0, 4)) }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #5C4F4A; margin-bottom: 30px;">
                                Would you like to introduce yourself with a thoughtful, slow-paced letter? Your privacy and mailing code remain guarded at all times.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto 30px auto;">
                                <tr>
                                    <td align="center" style="background-color: #783C46; border-radius: 4px; box-shadow: 0 2px 6px rgba(120, 60, 70, 0.3);">
                                        <a href="{{ route('discovery.index') }}" target="_blank" style="display: inline-block; padding: 14px 28px; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: none; letter-spacing: 1px;">
                                            Visit Discovery Desk & Compose Letter &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; color: #8A7972; border-top: 1px solid #E0D7D0; padding-top: 20px; margin-bottom: 0;">
                                With warm regards,<br>
                                <em>The Dearly Postmaster</em>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #FAF6EE; padding: 20px; border-top: 1px solid #E0D7D0; font-size: 11px; color: #8A7972;">
                            <p style="margin: 0 0 8px 0;">You received this notice because you took the Dearly questionnaire and opted into pen-pal discovery.</p>
                            <p style="margin: 0;">Dearly &bull; Pure Human Correspondence &bull; Never any public profiling</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

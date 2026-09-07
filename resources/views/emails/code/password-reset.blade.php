<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 15px !important; }
            .code { font-size: 22px !important; padding: 10px 0 !important; }
            h1 { font-size: 20px !important; }
        }
    </style>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" class="container" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <tr>
                    <td style="text-align: center; padding-bottom: 20px;">
                        <img src="{{ asset('img/cfls.png') }}" alt="Company Logo" style="max-width: 150px; height: auto;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1 style="font-size: 24px; color: #4f46e5; margin: 0 0 15px; line-height: 1.4; text-align:center;">
                            Bonjour {{ $user->name }}
                        </h1>
                        <p style="font-size: 16px; color: #333; margin: 0 0 20px; line-height: 1.6; text-align:center;">
                            Voici votre code pour réinitialiser votre mot de passe :
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <div class="code"
                             style="font-size: 28px; font-weight: 700; background-color:#eef2ff;
                                    padding: 15px 0; width: 70%; margin: 0 auto 20px;
                                    border-radius: 6px; letter-spacing: 5px; color:#4f46e5;">
                            {{ $verificationCode }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 20px; line-height: 1.5;">
                            Ce code expirera dans 15 minutes.<br>
                            Si vous n'avez pas demandé cette réinitialisation, ignorez ce message.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <p style="font-size: 16px; color: #4f46e5; font-weight: 600; margin: 0;">
                            {{ config('app.name') }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

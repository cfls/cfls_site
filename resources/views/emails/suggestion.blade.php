<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle suggestion de mot</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 15px !important; }
            h1 { font-size: 20px !important; }
            .button { padding: 10px 20px !important; font-size: 14px !important; }
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
                        <img src="{{ asset('img/cfls.png') }}" alt="Company Logo" style="max-width: 150px; height: auto; display: block; margin: 0 auto;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1 style="font-size: 24px; color: #4f46e5; margin: 0 0 15px; line-height: 1.4;">
                            Nouvelle suggestion de mot 🖐️
                        </h1>
                        <p style="font-size: 16px; color: #333; margin: 0 0 20px; line-height: 1.6;">
                            Un utilisateur a suggéré un mot pour le dictionnaire <strong>LSFBGo</strong>, actuellement introuvable.
                        </p>

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <table style="width: 100%; font-size: 14px; color: #374151; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 5px 0;"><strong>Mot suggéré :</strong></td>
                                <td style="padding: 5px 0;">{{ $suggestion->word }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Utilisateur :</strong></td>
                                <td style="padding: 5px 0;">
                                    {{ $suggestion->user->name ?? 'Utilisateur inconnu' }}
                                    (#{{ $suggestion->user_id }})
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Email :</strong></td>
                                <td style="padding: 5px 0;">{{ $suggestion->user->email ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Adresse IP :</strong></td>
                                <td style="padding: 5px 0;">{{ $suggestion->ip }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Date :</strong></td>
                                <td style="padding: 5px 0;">{{ $suggestion->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                        </table>

                        <p style="font-size: 16px; color: #333; line-height: 1.6; margin: 25px 0;">
                            Merci de vérifier si ce mot peut être ajouté au dictionnaire.
                        </p>

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <p style="font-size: 16px; color: #4f46e5; font-weight: 600; margin: 0; text-align: center;">
                            Cordialement,<br>
                            L'équipe de {{ config('app.name') }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

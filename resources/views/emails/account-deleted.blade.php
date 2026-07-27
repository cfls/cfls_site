<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression de compte LSFBGO</title>
</head>
<body style="font-family: sans-serif; background-color: #f9fafb; padding: 30px;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="600" style="background-color: white; border-radius: 8px; padding: 30px;">
                <tr>
                    <td>

                        <h2 style="color: #dc2626;">
                            Bonjour {{ $user->name }},
                        </h2>

                        <p>
                            Votre compte LSFBGO associé à l’adresse e-mail
                            <strong>{{ $user->email }}</strong>
                            a été <strong>supprimé</strong> avec succès.
                        </p>

                        <p>
                            Date de suppression :
                            <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                        </p>

                        <p style="margin-top: 20px;">
                            Si vous n’êtes pas à l’origine de cette action,
                            veuillez contacter notre équipe dès que possible.
                        </p>

                        <p style="margin-top: 30px;">
                            Merci d’avoir utilisé LSFBGO.
                        </p>

                        <div style="background-color: #eef2ff; padding: 20px; border-radius: 6px; margin: 25px 0; text-align: center;">
                            <p style="margin: 0 0 15px; color: #374151;">
                                Avant de partir, pourriez-vous nous dire pourquoi ? Cela nous aide à améliorer LSFBGO.
                            </p>
                            <a href="{{ $surveyUrl }}"
                               style="display: inline-block; background-color: #4f46e5; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                Répondre à l'enquête (2 min)
                            </a>
                        </div>

                        <p style="color: #4b5563; margin-top: 20px;">
                            Cordialement,<br>
                            Équipe {{ config('app.name') }}<br>
                            Avenue du Four à Briques, 3A<br>
                            1140 Evere (Bruxelles)<br>
                            <a href="mailto:info@cfls.be">info@cfls.be</a>
                        </p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquête de départ — LSFBGO</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <tr>
                    <td style="text-align: center; padding-bottom: 20px;">
                        <img src="{{ asset('img/cfls.png') }}" alt="Logo" style="max-width: 150px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 style="color: #4f46e5;">Aidez-nous à nous améliorer</h2>
                        <p style="color: #333; line-height: 1.6;">
                            Votre compte a bien été supprimé. Avant de partir, pourriez-vous nous dire pourquoi ?
                            Votre avis nous aide à améliorer LSFBGO.
                        </p>

                        <form method="POST" action="{{ route('deletion-survey.store') }}">
                            @csrf
                            <input type="hidden" name="user_name" value="{{ $name }}">
                            <input type="hidden" name="user_email" value="{{ $email }}">

                            <div style="margin: 20px 0;">
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="not_useful" required>
                                    L'application ne m'était plus utile
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="too_complicated">
                                    Trop compliqué à utiliser
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="found_alternative">
                                    J'ai trouvé une alternative
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="too_expensive">
                                    Prix trop élevé
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="technical_issues">
                                    Problèmes techniques
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="reason" value="other">
                                    Autre raison
                                </label>
                            </div>

                            <div style="margin: 20px 0;">
                                <label for="comment" style="display: block; margin-bottom: 8px; color: #374151;">
                                    Un commentaire ? (facultatif)
                                </label>
                                <textarea
                                        name="comment"
                                        id="comment"
                                        rows="4"
                                        style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;"
                                ></textarea>
                            </div>

                            <button
                                    type="submit"
                                    style="background-color: #4f46e5; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;"
                            >
                                Envoyer
                            </button>
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
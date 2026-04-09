<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Feedback</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#333;">
    
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="background:#111827; padding:24px; text-align:center;">
                            <h1 style="margin:0; font-size:22px; color:#ffffff;">
                                Nouveau Feedback reçu
                            </h1>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 20px; font-size:15px;">
                                Un utilisateur a envoyé un nouveau feedback depuis l’application.
                            </p>

                            <!-- BADGE TYPE -->
                            <p style="margin:0 0 20px;">
                                @php
                                    $colors = [
                                        'bug' => '#ef4444',
                                        'suggestion' => '#3b82f6',
                                        'question' => '#10b981',
                                    ];
                                @endphp

                                <span style="
                                    display:inline-block;
                                    padding:6px 12px;
                                    border-radius:999px;
                                    font-size:12px;
                                    color:#ffffff;
                                    background-color:{{ $colors[$feedback->type] ?? '#6b7280' }};
                                ">
                                    {{ ucfirst($feedback->type) }}
                                </span>
                            </p>

                            <!-- INFOS -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                
                                <tr>
                                    <td style="padding:12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:bold;">
                                        Utilisateur
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5e7eb;">
                                        {{ $feedback->user->name ?? 'ID: '.$feedback->user_id }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:bold;">
                                        Message
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5e7eb; line-height:1.6;">
                                        {{ $feedback->message }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:bold;">
                                        Question ID
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5e7eb;">
                                        {{ $feedback->question_id ?? 'Non spécifié' }}
                                    </td>
                                </tr>

                            </table>

                            <p style="margin-top:30px; font-size:13px; color:#6b7280;">
                                Ce message a été généré automatiquement par le système.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f9fafb; padding:16px; text-align:center; font-size:12px; color:#9ca3af;">
                            © {{ date('Y') }} CFLS - Tous droits réservés
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 15px !important; }
            .button { padding: 10px 20px !important; font-size: 14px !important; }
            .product-image { width: 60px !important; }
            h1 { font-size: 20px !important; }
            h2 { font-size: 18px !important; }
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
                        <img src="{{asset('img/cfls.png')}}" alt="Company Logo" style="max-width: 150px; height: auto; display: block; margin: 0 auto;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h1 style="font-size: 24px; color: #4f46e5; margin: 0 0 15px; line-height: 1.4;">
                            Bonjour {{ $order->user->name }},
                        </h1>
                        <p style="font-size: 16px; color: #333; margin: 0 0 20px; line-height: 1.6;">
                            Merci pour votre commande ! 🎉<br>
                            Elle est bien enregistrée. Nous préparons votre colis dès réception de votre paiement.
                        </p>

                        @php
                            $closure = app(\App\Services\ClosurePeriod::class);
                        @endphp
                        @if($closure->isClosed($order->created_at))
                            <p style="font-size: 16px; color: #92400e; background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 12px 16px; margin: 0 0 20px; line-height: 1.6;">
                                📅 {{ __('closure.checkout', ['reopen' => $closure->reopenDate()->translatedFormat('d/m/Y')]) }}
                            </p>
                        @endif

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px;">
                            🧾 Détails de la commande
                        </h2>
                        <table style="width: 100%; font-size: 14px; color: #374151; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 5px 0;"><strong>Commande n° :</strong></td>
                                <td style="padding: 5px 0;">{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Date :</strong></td>
                                <td style="padding: 5px 0;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Mode de livraison :</strong></td>
                                <td style="padding: 5px 0;">{{ ucfirst($order->delivery) }}</td>
                            </tr>
                        </table>

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px;">
                            👤 Détails du client
                        </h2>
                        <table style="width: 100%; font-size: 14px; color: #374151; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 5px 0;"><strong>Nom :</strong></td>
                                <td style="padding: 5px 0;">{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Email :</strong></td>
                                <td style="padding: 5px 0;">{{ $order->user->email }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0;"><strong>Téléphone :</strong></td>
                                <td style="padding: 5px 0;">{{ $order->user->telephone }}</td>
                            </tr>
                            @if(!empty($order->society))
                                <tr>
                                    <td style="padding: 5px 0;"><strong>Société :</strong></td>
                                    <td style="padding: 5px 0;">{{ $order->user->society }}</td>
                                </tr>
                            @endif
                            @if($order->delivery === 'livraison')
                                <tr>
                                    <td style="padding: 5px 0;"><strong>Adresse :</strong></td>
                                    <td style="padding: 5px 0;">
                                        {{ $order->user->address }}, {{$order->user->ville}}<br>
                                        {{ $order->user->postal_code }} {{ $order->user->region }}, {{ $order->user->province }}
                                    </td>
                                </tr>
                            @endif
                        </table>


                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px;">
                            📦 Produits commandés
                        </h2>
                        @foreach($order->products as $product)
                            <table style="width: 100%; margin-bottom: 20px; font-size: 14px; color: #374151;">
                                <tr>
                                    <td style="vertical-align: top; padding: 10px 0;">
                                        <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="product-image"
                                             style="width: 80px; height: auto; border-radius: 4px; margin-right: 15px;">
                                    </td>
                                    <td style="vertical-align: top; padding: 10px 0;">
                                        <p style="margin: 0 0 5px; font-weight: 600;">{{ $product->name }}</p>
                                        <p style="margin: 0 0 5px;">Quantité : {{ $product->pivot->quantity }}</p>
                                        @if (!empty($product->pivot->choix) && $product->pivot->choix != 0)
                                            <p style="margin: 0 0 5px;"><strong>Choix :</strong> {{ $product->pivot->choix }}</p>
                                        @endif
                                        <p style="margin: 0 0 5px;">Prix unitaire : {{ number_format($product->pivot->price, 2) }} €</p>
                                        <p style="margin: 0;"><strong>Sous-total :</strong> {{ number_format($product->pivot->quantity * $product->pivot->price, 2) }} €</p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px;">
                            💰 Résumé
                        </h2>
                        <table style="width: 100%; font-size: 14px; color: #374151; margin-bottom: 20px;">

                                <tr>
                                    <td style="padding: 5px 0;">Sous-total produits</td>
                                    <td style="padding: 5px 0; text-align: right;">
                                        {{ number_format($order->products->sum(fn($p) => $p->pivot->quantity * $p->pivot->price), 2) }} €
                                    </td>
                                </tr>
                            @if($order->member_discount > 0)
                                <tr>
                                    <td style="padding: 5px 0;">
                                        Réduction membres ({{ number_format($order->member_discount) }}%)
                                    </td>
                                    <td style="padding: 5px 0; text-align: right;">
                                        {{ number_format(
                                            $order->products->sum(function ($p) use ($order) {
                                                $subtotal = $p->pivot->quantity * $p->pivot->price;
                                                return $subtotal * ($order->member_discount / 100);
                                            }),
                                            2
                                        ) }} €
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding: 5px 0;">Frais de livraison</td>
                                <td style="padding: 5px 0; text-align: right;">
                                    {{ number_format($order->delivery_fee, 2) }} €
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; font-weight: 600;">Total</td>
                                <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                                    {{ number_format($order->total, 2) }} €
                                </td>
                            </tr>
                        </table>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ route('order.facture', $order->id) }}"
                               class="button"
                               style="background-color: #4f46e5; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-size: 16px; display: inline-block; transition: background-color 0.2s;">
                                Voir ma commande
                            </a>
                        </div>

                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin: 0 0 20px;">
                            📧 Une copie de cette facture a été envoyée à votre adresse e-mail.<br>
                            Si vous avez des questions, n'hésitez pas à nous contacter.
                        </p>

                        <p style="font-size: 16px; color: #4f46e5; font-weight: 600; margin: 0; text-align: center;">
                            Merci pour votre confiance,<br>
                            {{ config('app.name') }}
                        </p>
                        <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                        <h2 style="font-size: 20px; color: #111827; margin: 0 0 15px;">
                            🏦 Coordonnées bancaires
                        </h2>
                        <p style="font-size: 14px; color: #374151; margin: 0 0 10px; line-height: 1.6;">
                            CFLS – Centre Francophone de la Langue des Signes<br>
                            IBAN : <strong>BE38 3100 5385 3072</strong><br>
                        </p>

                        <h2 style="font-size: 20px; color: #111827; margin: 20px 0 15px;">
                            📞 Coordonnées de contact
                        </h2>
                        <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6;">
                            Adresse : Avenue du four à briques, 3A, 1140 Evere ( Bruxelles)<br>
                            Téléphone : +322 478 14 48<br>
                            Email : info@cfls.be
                        </p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

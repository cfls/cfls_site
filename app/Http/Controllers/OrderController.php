<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function store(Request $request)
    {


        // Convertir el JSON de productos en array
        $request->merge([
            'products' => json_decode($request->input('products'), true),
        ]);

        $rules = [
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'delivery' => 'required|in:retrait,livraison',
            'total' => 'required|numeric|min:0',
            'deliveryFee' => 'nullable|numeric|min:0',
            'products' => 'required|array',

            // Solo obligatorios si es "livraison"
            'address' => 'required_if:delivery,livraison|string|max:255',
            'ville' => 'required_if:delivery,livraison|string|max:255',
            'postal_code' => 'required_if:delivery,livraison|string|max:255',
            'province' => 'required_if:delivery,livraison|string|max:255',
            'region' => 'required_if:delivery,livraison|string|max:255',

            'society' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);




        // Crear usuario invitado si no está autenticado
        if (!Auth::check()) {
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['first_name'] . ' ' . $validated['second_name'],
                    'password' => bcrypt(Str::random(12)),
                    'role' => 'etudiant',
                    'telephone'    => $validated['telephone'],
                    'address'      => $validated['address'] ?? null,
                    'ville'        => $validated['ville'] ?? null,
                    'postal_code'  => $validated['postal_code'] ?? null,
                    'province'     => $validated['province'] ?? null,
                    'region'       => $validated['region'] ?? null,
                    'society'      => $validated['society'] ?? null,

                ]
            );

            Auth::login($user);
        } else {
            $user = Auth::user();

            if ($validated['delivery'] === 'livraison') {
                $user->update([
                    'telephone'    => $validated['telephone'],
                    'address'      => $validated['address'],
                    'ville'        => $validated['ville'],
                    'postal_code'  => $validated['postal_code'],
                    'province'     => $validated['province'],
                    'region'       => $validated['region'],
                    'society'      => $validated['society'],
                ]);
            }
        }


        $path = null;
        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs', 'public');
        }

        // Crear orden
        $order = Order::create([
            'user_id'      => $user->id, // ahora se relaciona la orden con el usuario
            'delivery'     => $validated['delivery'],
            'member_discount' => $user->getMemberDiscount() ?? 0,
            'total'        => $validated['total'],
            'delivery_fee' => $validated['deliveryFee'] ?? 0,
            'proof_path' => $path,
            'order_status' => 'attente',
        ]);



        // Asociar productos
        foreach ($validated['products'] as $product) {

            $randomCode = strtoupper(Str::random(8));
            $order->products()->attach($product['id'], [
                'quantity'    => $product['quantity'],
                'price'  =>      $product['price'],
                'choix'       => $product['choix'],
                'code'        => $randomCode,
            ]);
        }



        if ($request->boolean('newsletter')) {
            Newsletter::updateOrCreate(
                ['email' => $user->email],
                ['date' => now()->toDateString(), 'newsletter' => true]
            );
        }

        Mail::to($user->email)
            ->cc(config('mail.from.address'))
            ->send(new OrderConfirmationMail($order));

        return response()->json([
            'status' => 'ok',
            'message' => 'Order created successfully',
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);
    }

    public function facture(Order $order)
    {
        // Verificar si el usuario autenticado es el propietario de la orden
        if (Auth::check() && Auth::user()->id !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }
        // Verificar si la orden existe
        if (!$order) {
            abort(404, 'Order not found.');
        }
        // Verificar si la orden está pendiente
        if ($order->order_status !== 'attente'  && Auth::user()->id !== $order->user_id) {
            abort(403, 'You cannot view the invoice for this order.');
        }

        // Cargar imágenes relacionadas
        $order->load('products.mainImage');


        return view('orders.facture', compact('order'));
    }

    public function commanders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('products.mainImage')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('orders.commandes', compact('orders'));
    }









}

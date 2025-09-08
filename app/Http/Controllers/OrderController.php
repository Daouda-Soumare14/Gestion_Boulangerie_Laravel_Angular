<?php

namespace App\Http\Controllers;

use App\Events\DeliveryStatusUpdated;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateDeliveryStatusRequest;
use App\Mail\OrderCreated;
use App\Mail\OrderDelivered;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with(['items.product', 'user', 'details'])->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la récupération des commandes: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {
            $order = DB::transaction(function () use ($request) {
                $order = Order::create($request->only([
                    'user_id',
                    'order_status',
                    'delivery_status',
                    'payment_mode',
                    'total'
                ]));

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Vérif stock
                    if ($product->stock <= 0) {
                        throw new \Exception("Le produit {$product->name} est en rupture de stock.");
                    }
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuffisant pour {$product->name}. Restant: {$product->stock}");
                    }

                    // Décrémenter le stock
                    $product->decrement('stock', $item['quantity']);

                    foreach ($request->items as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ]);

                        //  Alerte si stock <= 10
                        if ($product->stock <= 10) {
                            // Tu peux envoyer un mail, notifier admin, ou logger
                            Log::warning("Stock faible pour {$product->name}: {$product->stock} restants.");
                        }
                    }

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'name' => $request->client_info['name'],
                        'email' => $request->client_info['email'],
                        'phone' => $request->client_info['phone'],
                        'address' => $request->client_info['address'],
                    ]);

                    return $order;
                }
            });

            // Recharge les relations pour renvoyer toutes les infos au frontend
            $order->load(['items.product', 'details']);
            Mail::to($order->details->email)->send(new OrderCreated($order));

            return response()->json([
                'message' => 'Commande créée avec succès',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création de la commande: ' . $e->getMessage(),
                'request_data' => $request->all()
            ], 500);
        }
    }



    /**
     * Mettre à jour le statut de livraison
     */
    public function updateDeliveryStatus(UpdateDeliveryStatusRequest $request, Order $order)
    {

        try {
            $order->update($request->validated());

            // Si la commande est maintenant livrée
            if ($request->delivery_status === 'livree' && $order->details?->email) {
                Mail::to($order->details->email)->queue(new OrderDelivered($order));
            }

            // Émettre l’événement en temps réel
            broadcast(new DeliveryStatusUpdated($order))->toOthers();

            return response()->json([
                'message' => 'Statut de livraison mis à jour avec succès',
                'order'   => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code'    => 500,
                'status_message' => 'Erreur lors de la mise à jour du statut de livraison: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mettre à jour le statut de la commande
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:validee,annulee'
        ]);

        try {
            $order->update([
                'order_status' => $request->order_status
            ]);

            return response()->json([
                'message' => 'Statut de commande mis à jour avec succès',
                'order'   => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code'    => 500,
                'status_message' => 'Erreur lors de la mise à jour du statut de commande: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            $order->load(['items.product', 'user', 'details']);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 404,
                'status_message' => 'Commande non trouvée: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        try {
            DB::transaction(function () use ($request, $order) {
                $order->update($request->only(['status', 'payment_mode', 'total']));
                $order->items()->delete();
                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);
                }

                // Mise à jour des détails client
                if ($order->details) {
                    $order->details()->update($request->client_info);
                } else {
                    $order->details()->create($request->client_info);
                }
            });

            return response()->json(['message' => 'Commande mise à jour avec succès']);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la mise à jour de la commande: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json(['message' => 'Commande supprimée avec succès'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la suppression de la commande: ' . $e->getMessage(),
            ], 500);
        }
    }
}

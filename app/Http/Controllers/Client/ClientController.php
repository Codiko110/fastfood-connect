<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Promotion;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\MenuService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private MenuService $menuService,
    ) {}

    public function accueil()
    {
        $categories = $this->menuService->getActiveCategories();
        $featuredProducts = Product::where('is_available', true)->where('is_featured', true)->take(4)->get();
        $popularProducts = Product::where('is_available', true)->orderBy('rating', 'desc')->take(4)->get();

        return view('client.accueil', compact('categories', 'featuredProducts', 'popularProducts'));
    }

    public function menu(Request $request)
    {
        $categories = $this->menuService->getActiveCategories();

        $filters = [
            'search' => $request->search,
            'sort' => $request->sort,
        ];

        if ($request->category && $request->category !== 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $filters['category_id'] = $category->id;
            }
        }

        $products = $this->menuService->getAvailableProducts($filters);

        return view('client.menu', compact('categories', 'products'));
    }

    public function detail(Product $product)
    {
        $product->load('extras', 'reviews', 'category');
        $recommended = $this->menuService->getRecommendedProducts($product);

        return view('client.detail-produit', compact('product', 'recommended'));
    }

    public function connexion()
    {
        return view('client.connexion');
    }

    public function inscription()
    {
        return view('client.inscription');
    }

    public function panier()
    {
        $cart = $this->cartService->getCart();
        $cart->load('items.product');

        return view('client.panier', compact('cart'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = $this->cartService->getCart();
        $this->cartService->addProduct($cart, $product, $request->extras ?? [], $request->quantity ?? 1);

        return redirect()->back()->with('success', 'Produit ajouté au panier');
    }

    public function updateCart(Request $request, CartItem $item)
    {
        $this->cartService->updateItemQuantity($item, $request->quantity);
        return redirect()->back();
    }

    public function removeFromCart(CartItem $item)
    {
        $this->cartService->removeItem($item);
        return redirect()->back();
    }

    public function livraison()
    {
        $cart = $this->cartService->getCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('client.panier')->with('error', 'Votre panier est vide');
        }

        return view('client.livraison', compact('cart'));
    }

    public function paiement(Request $request)
    {
        $cart = $this->cartService->getCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('client.panier');
        }

        session(['delivery_info' => $request->only(['name', 'phone', 'address', 'latitude', 'longitude'])]);

        return view('client.paiement', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('client.panier');
        }

        $deliveryInfo = session('delivery_info', []);

        $order = $this->orderService->createFromCart($cart, [
            'type' => 'delivery',
            'delivery_fee' => 0.6,
            'customer_name' => $request->name ?? $deliveryInfo['name'] ?? null,
            'customer_phone' => $request->phone ?? $deliveryInfo['phone'] ?? null,
            'customer_address' => $request->address ?? $deliveryInfo['address'] ?? null,
            'latitude' => $request->latitude ?? $deliveryInfo['latitude'] ?? null,
            'longitude' => $request->longitude ?? $deliveryInfo['longitude'] ?? null,
        ]);

        $this->orderService->addPayment($order, $request->payment_method ?? 'card');
        $this->cartService->clearCart($cart);
        session()->forget('delivery_info');
        $this->orderService->broadcastUpdate($order);

        return redirect()->route('client.commandes.suivis', $order);
    }

    public function commandes()
    {
        return view('client.historique');
    }

    public function orderDetail(Order $order)
    {
        $order->load('items.product', 'statuses');
        $statusLabels = $this->orderService->getStatusLabels();
        $initialStatuses = $this->orderService->formatStatusFlow($order->statuses);
        return view('client.commandes-detail', compact('order', 'statusLabels', 'initialStatuses'));
    }

    public function suivis(Order $order)
    {
        $order->load('items.product', 'statuses');
        $statusLabels = $this->orderService->getStatusLabels();
        $initialStatuses = $this->orderService->formatStatusFlow($order->statuses);
        return view('client.suivis', compact('order', 'statusLabels', 'initialStatuses'));
    }

    public function reorder(Order $order)
    {
        $cart = $this->cartService->getCart();
        $order->load('items');

        foreach ($order->items as $orderItem) {
            $existingItem = $cart->items()->where('product_id', $orderItem->product_id)->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $orderItem->quantity);
                $existingItem->update(['total_price' => $existingItem->quantity * $existingItem->unit_price]);
            } else {
                $cart->items()->create([
                    'product_id' => $orderItem->product_id,
                    'quantity' => $orderItem->quantity,
                    'extras' => $orderItem->extras,
                    'unit_price' => $orderItem->unit_price,
                    'total_price' => $orderItem->total_price,
                ]);
            }
        }

        session(['cart_count' => $cart->items()->sum('quantity')]);
        return redirect()->route('client.panier')->with('success', 'Panier mis à jour depuis la commande');
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $promotion = Promotion::where('name', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promotion) {
            return redirect()->back()->with('error', 'Code promo invalide ou expiré');
        }

        session(['promo' => $promotion]);
        return redirect()->back()->with('success', 'Code promo appliqué avec succès');
    }

    public function newsletter(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        session(['newsletter_email' => $request->email]);
        return redirect()->back()->with('success', 'Inscription à la newsletter réussie');
    }

    public function profil()
    {
        if (!auth()->check()) {
            return redirect()->route('client.connexion');
        }

        $ordersCount = Order::where('user_id', auth()->id())->count();
        return view('client.profil', compact('ordersCount'));
    }
}

<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Review;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\MenuService;
use App\Services\TableService;
use App\Http\Requests\SubmitReviewRequest;
use Illuminate\Http\Request;

class TableKioskController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private MenuService $menuService,
        private TableService $tableService,
    ) {}

    public function identity()
    {
        $tables = $this->tableService->getAvailableTables();
        return view('table.identite-table', compact('tables'));
    }

    public function selectTable(Request $request)
    {
        $request->validate(['table_id' => 'required|exists:tables,id']);

        $selected = $this->tableService->selectTable($request->table_id);

        if (!$selected) {
            return redirect()->back()->with('error', 'Cette table est déjà occupée.');
        }

        return redirect()->route('table.menu');
    }

    public function menu()
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $categories = $this->menuService->getActiveCategories();
        $products = Product::where('is_available', true)->with('category')->orderBy('sort_order')->get();
        $tableNumber = session('table_number');

        return view('table.menu', compact('categories', 'products', 'tableNumber'));
    }

    public function detail(Product $product)
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $product->load('extras', 'category');
        $recommended = $this->menuService->getRecommendedProducts($product, 3);

        return view('table.detail', compact('product', 'recommended'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = $this->cartService->getCart();
        $this->cartService->addProduct($cart, $product, $request->extras ?? [], $request->quantity ?? 1);

        return redirect()->route('table.panier')->with('success', 'Ajouté au panier');
    }

    public function panier()
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $cart = $this->cartService->getCart();
        $cart->load('items.product');
        $tableNumber = session('table_number');

        return view('table.panier-table', compact('cart', 'tableNumber'));
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

    public function placeOrder(Request $request)
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('table.menu');
        }

        $order = $this->orderService->createFromCart($cart, [
            'type' => 'table',
            'table_id' => session('table_id'),
            'customer_name' => 'Table ' . session('table_number'),
            'delivery_fee' => 0,
        ]);

        $this->cartService->clearCart($cart);
        $this->orderService->broadcastUpdate($order);

        return redirect()->route('table.confirmation', $order);
    }

    public function confirmation(Order $order)
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $order->load('items.product');
        $tableNumber = session('table_number');

        return view('table.confirmation', compact('order', 'tableNumber'));
    }

    public function suivis(Order $order)
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $order->load('items.product', 'statuses');
        $tableNumber = session('table_number');
        $statusLabels = $this->orderService->getStatusLabels();
        $initialStatuses = $this->orderService->formatStatusFlow($order->statuses);

        return view('table.suivis', compact('order', 'tableNumber', 'statusLabels', 'initialStatuses'));
    }

    public function historique()
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $sessionStartedAt = session('session_started_at');
        $orders = $this->tableService->getSessionOrders(session('table_id'), $sessionStartedAt);
        $tableNumber = session('table_number');
        $totalSession = $orders->sum('total');

        return view('table.historique', compact('orders', 'tableNumber', 'totalSession'));
    }

    public function service()
    {
        if (!session('table_id')) {
            return redirect()->route('table.identity');
        }

        $tableNumber = session('table_number');
        return view('table.service', compact('tableNumber'));
    }

    public function serviceRequest(Request $request)
    {
        $request->validate(['type' => 'required|string|max:255']);

        $this->tableService->requestService(
            session('table_id'),
            session('table_number'),
            $request->type
        );

        return redirect()->back()->with('success', 'Demande de service envoyée.');
    }

    public function requestBill(Request $request)
    {
        $this->tableService->requestService(
            session('table_id'),
            session('table_number'),
            'bill'
        );

        return redirect()->back()->with('success', "Demande d'addition envoyée.");
    }

    public function submitFeedback(SubmitReviewRequest $request)
    {
        Review::create($request->validated());
        return redirect()->back()->with('success', 'Merci pour votre avis !');
    }

    public function leaveTable()
    {
        $tableId = session('table_id');

        if ($tableId) {
            $this->tableService->leaveTable($tableId);
        }

        return redirect()->route('table.identity')->with('success', 'Merci, à bientôt !');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\Delivery;
use App\Services\MenuService;
use App\Services\OrderService;
use App\Services\StatisticsService;
use App\Services\DeliveryService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\StoreRevenueRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Requests\UpdateDeliveryStatusRequest;
use App\Http\Requests\AssignDeliveryRequest;
use App\Events\TableStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct(
        private MenuService $menuService,
        private OrderService $orderService,
        private StatisticsService $statisticsService,
        private DeliveryService $deliveryService,
    ) {}

    public function dashboard()
    {
        $totalOrders = Order::count();
        $dailyRevenue = Order::whereDate('created_at', today())->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $recentOrders = Order::with('items.product')->latest()->take(5)->get();
        $weeklySales = $this->statisticsService->getWeeklySales();
        $featuredProduct = Product::where('is_featured', true)->where('is_available', true)->first();

        return view('admin.dashboard', compact(
            'totalOrders', 'dailyRevenue', 'pendingOrders', 'preparingOrders',
            'recentOrders', 'weeklySales', 'featuredProduct'
        ));
    }

    public function orders(Request $request)
    {
        $query = Order::with('items.product', 'table');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ilike', '%' . $search . '%')
                  ->orWhere('customer_name', 'ilike', '%' . $search . '%');
            });
        }

        $orders = $query->orderByRaw("CASE status
            WHEN 'pending' THEN 1
            WHEN 'confirmed' THEN 2
            WHEN 'preparing' THEN 3
            WHEN 'ready' THEN 4
            WHEN 'delivered' THEN 5
            WHEN 'cancelled' THEN 6
            ELSE 7 END")
            ->latest()
            ->paginate(10);

        return view('admin.gestion-commandes', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        $order->load('items.product', 'statuses', 'payment', 'delivery');
        $statusLabels = $this->orderService->getStatusLabels();
        $initialStatuses = $this->orderService->formatStatusFlow($order->statuses);
        return view('admin.details-commandes', compact('order', 'statusLabels', 'initialStatuses'));
    }

    public function updateOrderStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        $this->orderService->addStatus($order, $request->status, $request->notes);
        $this->orderService->broadcastUpdate($order);

        return redirect()->back()->with('success', 'Statut mis à jour');
    }

    public function menu(Request $request)
    {
        $categories = $this->menuService->getAllCategoriesWithCount();

        $filters = [
            'search' => $request->search,
        ];

        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $filters['category_id'] = $category->id;
            }
        }

        $products = $this->menuService->getAdminProducts($filters);
        return view('admin.categories', compact('categories', 'products'));
    }

    public function createProduct()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.plats', compact('categories'));
    }

    public function storeProduct(StoreProductRequest $request)
    {
        $data = $request->validated();
        $this->menuService->createProduct($data, $request->hasFile('image') ? ['file' => $request->file('image')] : null);

        return redirect()->route('admin.menu')->with('success', 'Plat créé avec succès');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.plats', compact('categories', 'product'));
    }

    public function updateProduct(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $this->menuService->updateProduct($product, $data, $request->hasFile('image') ? ['file' => $request->file('image')] : null);

        return redirect()->route('admin.menu')->with('success', 'Plat mis à jour');
    }

    public function destroyProduct(Product $product)
    {
        $this->menuService->deleteProduct($product);
        return redirect()->back()->with('success', 'Plat supprimé');
    }

    public function toggleProduct(Product $product)
    {
        $this->menuService->toggleAvailability($product);
        return redirect()->back();
    }

    public function categories()
    {
        $categories = $this->menuService->getAllCategoriesWithCount();
        return view('admin.categories-index', compact('categories'));
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $this->menuService->createCategory($request->validated());
        return redirect()->back()->with('success', 'Catégorie créée');
    }

    public function updateCategory(StoreCategoryRequest $request, Category $category)
    {
        $this->menuService->updateCategory($category, $request->validated());
        return redirect()->back()->with('success', 'Catégorie mise à jour');
    }

    public function destroyCategory(Category $category)
    {
        $deleted = $this->menuService->deleteCategory($category);

        if (!$deleted) {
            return redirect()->back()->with('error', 'Impossible de supprimer une catégorie avec des produits');
        }

        return redirect()->back()->with('success', 'Catégorie supprimée');
    }

    public function tables()
    {
        $tables = Table::orderBy('table_number')->get();
        return view('admin.tables', compact('tables'));
    }

    public function storeTable(StoreTableRequest $request)
    {
        Table::create($request->validated());
        return redirect()->back()->with('success', 'Table ajoutée');
    }

    public function updateTableStatus(Request $request, Table $table)
    {
        $request->validate(['status' => 'required|in:free,occupied,ordering']);
        $table->update(['status' => $request->status]);
        broadcastSafe(new TableStatusUpdated($table));
        return redirect()->back();
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return redirect()->back()->with('success', 'Table supprimée');
    }

    public function deliveries(Request $request)
    {
        $deliveries = $this->deliveryService->getDeliveriesWithFilters($request->search);
        $counts = $this->deliveryService->getDeliveryCounts();

        return view('admin.livraisons', compact('deliveries') + $counts);
    }

    public function assignDelivery(AssignDeliveryRequest $request, Delivery $delivery)
    {
        $this->deliveryService->assignDelivery($delivery, $request->delivery_person_name);
        return redirect()->back()->with('success', 'Livraison assignée');
    }

    public function updateDeliveryStatus(UpdateDeliveryStatusRequest $request, Delivery $delivery)
    {
        $this->deliveryService->updateStatus($delivery, $request->status);
        return redirect()->back()->with('success', 'Statut de livraison mis à jour');
    }

    public function statistics()
    {
        $stats = $this->statisticsService->getFullStatistics();

        return view('admin.statistiques', $stats);
    }

    public function storeRevenue(StoreRevenueRequest $request)
    {
        $result = $this->statisticsService->storeRevenue($request->validated());

        if (is_array($result) && isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->back()->with('success', 'Opération enregistrée');
    }

    public function settings()
    {
        return view('admin.parametres');
    }

    public function updateSettings(Request $request)
    {
        return redirect()->back()->with('success', 'Paramètres mis à jour');
    }

    public function loginForm()
    {
        return view('admin.connexion');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Accès non autorisé']);
        }

        return back()->withErrors(['email' => "L'email ou le mot de passe est incorrect."]);
    }
}

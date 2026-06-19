<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Table\TableKioskController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\RealtimeController;

// Redirect root
Route::redirect('/', '/accueil');

// ===== AUTH ROUTES =====
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Default login route for auth middleware redirect
Route::get('/login', function () { return redirect()->route('admin.login'); })->name('login');

// ===== CLIENT ROUTES =====
Route::prefix('/')->name('client.')->group(function () {
    Route::get('/accueil', [ClientController::class, 'accueil'])->name('accueil');
    Route::get('/menu', [ClientController::class, 'menu'])->name('menu');
    Route::get('/menu/{product}', [ClientController::class, 'detail'])->name('detail');
    Route::get('/connexion', [ClientController::class, 'connexion'])->name('connexion');
    Route::get('/inscription', [ClientController::class, 'inscription'])->name('inscription');

    // Cart
    Route::get('/panier', [ClientController::class, 'panier'])->name('panier');
    Route::post('/panier/ajouter/{product}', [ClientController::class, 'addToCart'])->name('panier.ajouter');
    Route::post('/panier/update/{item}', [ClientController::class, 'updateCart'])->name('panier.update');
    Route::delete('/panier/remove/{item}', [ClientController::class, 'removeFromCart'])->name('panier.remove');

    // Checkout
    Route::get('/livraison', [ClientController::class, 'livraison'])->name('livraison');
    Route::post('/paiement', [ClientController::class, 'paiement'])->name('paiement');
    Route::post('/commander', [ClientController::class, 'placeOrder'])->name('commander');

    // Orders
    Route::get('/commandes', [ClientController::class, 'commandes'])->name('commandes');
    Route::get('/commandes/{order}', [ClientController::class, 'orderDetail'])->name('commandes.detail');
    Route::get('/commandes/{order}/suivis', [ClientController::class, 'suivis'])->name('commandes.suivis');
    Route::post('/commandes/{order}/reorder', [ClientController::class, 'reorder'])->name('commandes.reorder');

    // Promo
    Route::post('/promo/apply', [ClientController::class, 'applyPromo'])->name('promo.apply');

    // Newsletter
    Route::post('/newsletter', [ClientController::class, 'newsletter'])->name('newsletter');

    // Profile
    Route::get('/profil', [ClientController::class, 'profil'])->name('profil');
});

// ===== ADMIN AUTH (public) =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/connexion', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/connexion', [AdminController::class, 'login'])->name('login.post');
});

// ===== ADMIN ROUTES (protected) =====
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/commandes', [AdminController::class, 'orders'])->name('orders');
    Route::get('/commandes/{order}', [AdminController::class, 'orderDetails'])->name('orders.details');
    Route::post('/commandes/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Menu / Products
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::get('/menu/creer', [AdminController::class, 'createProduct'])->name('menu.creer');
    Route::post('/menu', [AdminController::class, 'storeProduct'])->name('menu.store');
    Route::get('/menu/{product}/edit', [AdminController::class, 'editProduct'])->name('menu.edit');
    Route::put('/menu/{product}', [AdminController::class, 'updateProduct'])->name('menu.update');
    Route::delete('/menu/{product}', [AdminController::class, 'destroyProduct'])->name('menu.destroy');
    Route::post('/menu/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('menu.toggle');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // Tables
    Route::get('/tables', [AdminController::class, 'tables'])->name('tables');
    Route::post('/tables', [AdminController::class, 'storeTable'])->name('tables.store');
    Route::post('/tables/{table}/status', [AdminController::class, 'updateTableStatus'])->name('tables.status');
    Route::delete('/tables/{table}', [AdminController::class, 'destroyTable'])->name('tables.destroy');

    // Deliveries
    Route::get('/livraisons', [AdminController::class, 'deliveries'])->name('deliveries');
    Route::post('/livraisons/{delivery}/assign', [AdminController::class, 'assignDelivery'])->name('deliveries.assign');
    Route::post('/livraisons/{delivery}/status', [AdminController::class, 'updateDeliveryStatus'])->name('deliveries.status');

    // Statistics
    Route::get('/statistiques', [AdminController::class, 'statistics'])->name('statistics');
    Route::post('/statistiques/revenue', [AdminController::class, 'storeRevenue'])->name('statistics.revenue');

    // Settings
    Route::get('/parametres', [AdminController::class, 'settings'])->name('settings');
    Route::post('/parametres', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// ===== TABLE ROUTES =====
Route::prefix('table')->name('table.')->group(function () {
    Route::get('/', [TableKioskController::class, 'identity'])->name('identity');
    Route::post('/select', [TableKioskController::class, 'selectTable'])->name('select');
    Route::post('/quitter', [TableKioskController::class, 'leaveTable'])->name('quitter');
    Route::get('/menu', [TableKioskController::class, 'menu'])->name('menu');
    Route::get('/menu/{product}', [TableKioskController::class, 'detail'])->name('detail');

    // Cart
    Route::get('/panier', [TableKioskController::class, 'panier'])->name('panier');
    Route::post('/panier/ajouter/{product}', [TableKioskController::class, 'addToCart'])->name('panier.ajouter');
    Route::post('/panier/update/{item}', [TableKioskController::class, 'updateCart'])->name('panier.update');
    Route::post('/panier/remove/{item}', [TableKioskController::class, 'removeFromCart'])->name('panier.remove');

    // Order
    Route::post('/commander', [TableKioskController::class, 'placeOrder'])->name('commander');
    Route::get('/commande/{order}/confirmation', [TableKioskController::class, 'confirmation'])->name('confirmation');
    Route::get('/commande/{order}/suivis', [TableKioskController::class, 'suivis'])->name('suivis');

    // History & Service
    Route::get('/historique', [TableKioskController::class, 'historique'])->name('historique');
    Route::get('/service', [TableKioskController::class, 'service'])->name('service');
    Route::post('/service/request', [TableKioskController::class, 'serviceRequest'])->name('service.request');
    Route::post('/service/bill', [TableKioskController::class, 'requestBill'])->name('service.bill');
    Route::post('/feedback', [TableKioskController::class, 'submitFeedback'])->name('feedback');
});

// ===== API REALTIME ROUTES =====
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/table/orders', [RealtimeController::class, 'tableOrders'])->name('table.orders');
    Route::get('/admin/orders', [RealtimeController::class, 'adminOrders'])->name('admin.orders');
    Route::get('/admin/stats', [RealtimeController::class, 'dashboardStats'])->name('admin.stats');
    Route::get('/table/order/{order}/status', [RealtimeController::class, 'orderStatus'])->name('table.order.status');
});

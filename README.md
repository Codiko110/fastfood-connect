<picture align="center">
  <source srcset="https://socialify.git.ci/user/fastfood-connect/image?description=1&font=Inter&forks=1&issues=1&language=1&name=1&owner=1&pattern=Solid&pulls=1&stargazers=1&theme=Dark" />
  <img alt="FastFood Connect banner" />
</picture>

# 🍔 FastFood Connect — FlashFood

**FastFood Connect** (branded as **FlashFood**) is a full-stack restaurant management system with three integrated interfaces: **Customer online ordering**, **Table kiosk ordering**, and an **Admin management dashboard**. Orders sync in real-time across all interfaces via WebSockets.

---

## ✨ Features

### 🧑‍🍳 Customer Interface
- Browse menu by categories with search and sorting
- Product detail page with extras/supplements
- Shopping cart with quantity management
- Checkout with delivery geolocation and promo codes
- Real-time order tracking (WebSocket + polling fallback)
- Order history and re-ordering
- Newsletter subscription

### 🪟 Table Kiosk Interface
- Table selection and session management
- On-site menu browsing and ordering
- Real-time order status tracking
- Service requests: call waiter, request water, ask for assistance
- Request bill and submit feedback
- Order history per session

### ⚙️ Admin Dashboard
- Overview stats: revenue, orders count, weekly sales chart
- Order management with status workflow (pending → confirmed → preparing → ready → delivered)
- Menu CRUD with categories, extras, and toggle availability
- Category management with product count
- Table management with status (free / occupied / ordering)
- Delivery assignment and tracking
- Statistics: revenue analytics, average basket, prep time, top products, category breakdown
- Manual revenue logging (income / expense)
- Settings panel

### 🔄 Real-Time
- **WebSocket channels** (via Laravel Reverb): `orders`, `table.{id}`, `tables`, `deliveries`, `menu`
- **6 broadcast events**: order status, table status, service requests, delivery status, menu updates
- **Polling fallback** — functions seamlessly even without WebSocket server running

### 💰 Multi-Currency
Prices stored in EUR internally, displayed in **MGA (Malagasy Ariary)** at a rate of 5,000 Ar/EUR.

---

## 🛠️ Tech Stack

| Layer       | Technology                                  |
|-------------|---------------------------------------------|
| Backend     | PHP 8.3, Laravel 13                         |
| Database    | SQLite                                      |
| WebSocket   | Laravel Reverb                              |
| Frontend    | Alpine.js, Tailwind CSS 4, Laravel Echo     |
| Maps        | Leaflet.js                                  |
| Icons       | Heroicons                                   |
| Build       | Vite, npm                                   |

---

## ⚡ Getting Started

### Prerequisites
- PHP ^8.3 + [Composer](https://getcomposer.org)
- Node.js + npm

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd fastfood-connect

# Install dependencies & set up the project
composer run setup

# Or manually:
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

### Development

Run all dev processes concurrently (HTTP server, queue, logs, Vite HMR, Reverb):

```bash
composer run dev
```

Or serve on your local network:

```bash
composer run serve:network
```

### Testing

```bash
composer run test
```

---

## 🗺️ Project Structure

```
├── app/
│   ├── Events/          # 6 real-time broadcast events
│   ├── Http/Controllers # 5 controllers (Admin, Auth, Client, Table, API)
│   ├── Http/Requests/   # 8 form request validations
│   ├── Models/          # 13 Eloquent models
│   └── Services/        # 6 service classes (business logic)
├── resources/views/     # Blade templates (30+ views)
│   ├── admin/           # 10 admin dashboard views
│   ├── client/          # 12 customer interface views
│   └── table/           # 8 table kiosk views
├── routes/
│   ├── web.php          # All HTTP routes
│   └── channels.php     # 5 WebSocket channels
├── database/migrations/ # 19 migration files
└── tests/               # Unit & Feature tests
```

---

## 📡 Event System

| Event                     | Channel              | Description                    |
|---------------------------|----------------------|--------------------------------|
| `OrderStatusUpdated       | orders               | Order status changed           |
| `TableStatusUpdated       | tables, table.{id}   | Table became free/occupied     |
| `ServiceRequestCreated    | table.{id}           | Customer requested assistance  |
| `DeliveryStatusUpdated    | deliveries           | Delivery status changed        |
| `MenuProductUpdated       | menu                 | Product added/updated/deleted  |
| `MenuCategoryUpdated      | menu                 | Category added/updated/deleted |

---

## 🐞 Known Issues

- Order tracking page shows "No tracking available" for delivered orders in admin and table interfaces.
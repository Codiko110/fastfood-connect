Alpine.store('app', {
    cartCount: 0,
    notifications: [],
    notifCount: 0,
    readyCount: 0,
    readyPing: false,
    activeOrders: [],
    tableId: null,
    activeServiceRequest: null,

    setCartCount(count) {
        this.cartCount = count
    },

    setNotifications(orders) {
        this.activeOrders = orders
        this.notifCount = orders.length
        this.readyCount = orders.filter(o => o.status === 'ready').length
        if (this.readyCount > 0) this.readyPing = true
    },
})

Alpine.store('admin', {
    orders: [],
    stats: null,
    lastUpdate: null,

    setOrders(orders) {
        this.orders = orders
        this.lastUpdate = Date.now()
    },

    setStats(stats) {
        this.stats = stats
        this.lastUpdate = Date.now()
    },
})

Alpine.store('clientOrders', {
    orders: JSON.parse(localStorage.getItem('ff_orders') || '[]'),

    save(order) {
        const exists = this.orders.find(o => o.id === order.id)
        if (!exists) {
            this.orders.unshift({
                id: order.id,
                order_number: order.order_number,
                status: order.status,
                status_label: order.status_label,
                total: order.total,
                items_count: order.items_count,
                customer_name: order.customer_name,
                type: order.type,
                created_at: order.created_at,
            })
            localStorage.setItem('ff_orders', JSON.stringify(this.orders))
        }
    },

    getAll() {
        return this.orders
    },

    clear() {
        this.orders = []
        localStorage.removeItem('ff_orders')
    },
})

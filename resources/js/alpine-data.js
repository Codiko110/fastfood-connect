const STATUS_LABELS = {
    pending: 'Nouvelle', confirmed: 'Confirmée', preparing: 'En préparation',
    ready: 'Prête', delivered: 'Livrée', cancelled: 'Annulée',
}

Alpine.data('orderTracker', (orderId, initialStatus, interval = 6000, initialLabel = '', initialStatuses = []) => ({
    currentStatus: initialStatus,
    statuses: initialStatuses,
    statusLabel: initialLabel || STATUS_LABELS[initialStatus] || '',
    itemsCount: 0,
    total: '',

    init() {
        this.fetchStatus()
        setInterval(() => this.fetchStatus(), interval)
        if (window.Echo) {
            window.Echo.channel('orders')
                .listen('.order.updated', (e) => {
                    if (e.order && e.order.id == orderId) {
                        this.handleUpdate(e.order)
                    }
                })
        }
    },

    handleUpdate(order) {
        this.currentStatus = order.status
        this.statuses = order.statuses ?? []
        this.statusLabel = order.status_label
        this.itemsCount = order.items_count
        this.total = order.total
        window.Alpine.$store.app.setNotifications(window.Alpine.$store.app.activeOrders)
    },

    fetchStatus() {
        fetch(`/api/table/order/${orderId}/status`)
            .then(r => r.json())
            .then(data => {
                this.currentStatus = data.status
                this.statuses = data.statuses ?? []
                this.statusLabel = data.status_label
                this.itemsCount = data.items_count
                this.total = data.total
                this.$store.app.setNotifications(this.$store.app.activeOrders)
            })
            .catch(() => {})
    },

    isCompleted(index) {
        return index < this.statuses.length - 1
    },
    isActive(index) {
        return index === this.statuses.length - 1
    },
}))

Alpine.data('tableNotifier', (endpoint, interval = 8000) => ({
    init() {
        this.fetchData()
        this.startWebSocket()
    },

    startWebSocket() {
        const tableId = window.Alpine.$store.app.tableId
        if (!tableId || !window.Echo) return
        window.Echo.channel('table.' + tableId)
            .listen('.order.updated', () => {
                this.fetchData()
            })
    },

    fetchData() {
        fetch(endpoint)
            .then(r => r.json())
            .then(data => this.$store.app.setNotifications(data.orders ?? []))
            .catch(() => {})
    }
}))

Alpine.data('adminNotifier', (endpoint, interval = 6000) => ({
    init() {
        this.fetchData()
        if (window.Echo) {
            window.Echo.channel('orders')
                .listen('.order.updated', (e) => {
                    if (e.order) {
                        this.$store.admin.setOrders([e.order])
                    }
                    if (e.stats) {
                        this.$store.admin.setStats(e.stats)
                    }
                })
        }
    },

    fetchData() {
        fetch(endpoint)
            .then(r => r.json())
            .then(data => {
                if (data.orders) this.$store.admin.setOrders(data.orders)
                if (data.stats) this.$store.admin.setStats(data.stats)
            })
            .catch(() => {})
    }
}))

Alpine.data('statsBoard', () => ({
    revenue: 0,
    orderRevenue: 0,
    manualExpenses: 0,
    averageBasket: 0,
    averagePrep: 0,
    totalProducts: 0,
    totalCategories: 0,
    topProducts: [],

    init() {
        if (window.Echo) {
            window.Echo.channel('orders')
                .listen('.order.updated', (e) => {
                    if (e.stats) {
                        this.revenue = e.stats.daily_revenue
                    }
                })
        }
    }
}))


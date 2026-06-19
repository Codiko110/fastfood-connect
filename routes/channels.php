<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders', function () {
    return true;
});

Broadcast::channel('table.{tableId}', function ($user, $tableId) {
    return true;
});

Broadcast::channel('tables', function () {
    return true;
});

Broadcast::channel('deliveries', function () {
    return true;
});

Broadcast::channel('menu', function () {
    return true;
});

<?php

use App\Models\Order;
use Illuminate\Http\Request;
use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('customer.panel.index')
    ->push(__('Dashboard'));
Breadcrumbs::for('customer.panel.profile')
    ->push(__('User Profile'));
Breadcrumbs::for('customer.panel.orders')
    ->push(__('Orders List'));
Breadcrumbs::for('customer.panel.order.show')
    ->push(__('Orders List'), route('customer.panel.orders'))
    ->callback(function (Trail $trail, Order $order, Request $request): Trail {
        return $trail->push($order->id);
    });
Breadcrumbs::for('customer.panel.invoices')
    ->push(__('Invoices List'));

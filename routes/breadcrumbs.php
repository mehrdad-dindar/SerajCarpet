<?php

use Illuminate\Http\Request;
use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('customer.panel.index')
    ->push(__('Dashboard'));
Breadcrumbs::for('customer.panel.profile')
    ->push(__('User Profile'));
Breadcrumbs::for('customer.panel.orders')
    ->push(__('Orders List'));
Breadcrumbs::for('customer.panel.invoices')
    ->push(__('Invoices List'));

<!-- Main Sidebar Container -->
@php

$c = Request::segment(1);
$m = Request::segment(2);
$RoleName = Auth::user()->getRoleNames();

@endphp

<aside class="main-sidebar elevation-4 sidebar-light-info">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard')  }}" class="brand-link navbar-info">
        <img src="{{ asset('img/favicon.png') }}" alt="{{ $ApplicationSetting->item_name }}" class="brand-image" style="opacity: .8; width :32px; height : 32px">
        <span class="brand-text font-weight-light">{{ $ApplicationSetting->item_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php
            if(Auth::user()->photo == NULL)
            {
                $photo = "img/profile/male.png";
            } else {
                $photo = Auth::user()->photo;
            }
        ?>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset($photo) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info my-auto">
                {{ Auth::user()->name }}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if($c == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('item.index') }}" class="nav-link @if($c == 'item') active @endif ">
                        <i class="fas fa-code-branch nav-icon"></i>
                        <p>@lang('Items')</p>
                    </a>
                </li>

                <li class="nav-item has-treeview @if($c == 'customer') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'customer') active @endif">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            @lang('Incomes')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('revenue.index') }}" class="nav-link @if($c == 'revenue') active @endif ">
                                <i class="fas fa-coins"></i>
                                <p>@lang('Revenue')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link @if($c == 'customer') active @endif ">
                                <i class="fas fa-user-plus"></i>
                                <p>@lang('Customer')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview @if($c == 'vendor') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'vendor') active @endif">
                        <i class="nav-icon fas fa-minus"></i>
                        <p>
                            @lang('Expenses')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payment.index') }}" class="nav-link @if($c == 'payment') active @endif ">
                                <i class="fab fa-ethereum"></i>
                                <p>@lang('Payment')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.index') }}" class="nav-link @if($c == 'vendor') active @endif ">
                                <i class="fas fa-user-minus"></i>
                                <p>@lang('Vendor')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview @if($c == 'account' || $c == 'transfers' || $c == 'transactions') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'account' || $c == 'transfers' || $c == 'transactions' ) active @endif">
                        <i class="nav-icon fas fa-university"></i>
                        <p>
                            @lang('Banking')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('account.index') }}" class="nav-link @if($c == 'account') active @endif ">
                                <i class="fas fa-user-circle"></i>
                                <p>@lang('Accounts')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfer.index') }}" class="nav-link @if($c == 'transfer') active @endif ">
                                <i class="fas fa-exchange-alt"></i>
                                <p>@lang('Transfers')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transaction.index') }}" class="nav-link @if($c == 'transactions') active @endif ">
                                <i class="fas fa-handshake"></i>
                                <p>@lang('Transactions')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link @if($c == 'reconciliations') active @endif ">
                                <i class="fab fa-creative-commons-sampling-plus"></i>
                                <p>@lang('Reconciliations')</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'general' || $c == 'category' || $c == 'currency' || $c == 'tax' || $c == 'offline-payment' ) menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'general' || $c == 'category' || $c == 'currency' || $c == 'tax' || $c == 'offline-payment' ) active @endif">
                        <i class="nav-icon fa fa-cogs"></i>
                        <p>
                            @lang('Settings')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link @if($c == 'roles') active @endif ">
                                <i class="fas fa-cube nav-icon"></i>
                                <p>@lang('Role Management')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link @if($c == 'users') active @endif ">
                                <i class="fa fa-users nav-icon"></i>
                                <p>@lang('User Management')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('apsetting') }}" class="nav-link @if($c == 'apsetting' && $m == null) active @endif ">
                                <i class="fa fa-globe nav-icon"></i>
                                <p>@lang('Application Settings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('smtp.index') }}" class="nav-link @if($c == 'smtp') active @endif ">
                                <i class="fas fa-mail-bulk nav-icon"></i>
                                <p>@lang('Smtp Settings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('general') }}" class="nav-link @if($c == 'general') active @endif ">
                                <i class="fas fa-align-left nav-icon"></i>
                                <p>@lang('General Settings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link @if($c == 'category') active @endif ">
                                <i class="fas fa-code-branch nav-icon"></i>
                                <p>@lang('Category')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('currency.index') }}" class="nav-link @if($c == 'currency') active @endif ">
                                <i class="fas fa-coins nav-icon"></i>
                                <p>@lang('Currencies')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tax.index') }}" class="nav-link @if($c == 'tax') active @endif ">
                                <i class="fas fa-percentage nav-icon"></i>
                                <p>@lang('Tax rates')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('offline-payment.index') }}" class="nav-link @if($c == 'offline-payment') active @endif ">
                                <i class="fas fa-money-check nav-icon"></i>
                                <p>@lang('Offline Payments')</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>

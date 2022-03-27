@php
$c = Request::segment(1);
$m = Request::segment(2);
$RoleName = Auth::user()->getRoleNames();
@endphp

<aside class="main-sidebar elevation-4 sidebar-light-info">
    <a href="{{ route('dashboard')  }}" class="brand-link navbar-info">
        <img src="{{ asset('img/favicon.png') }}" alt="{{ $ApplicationSetting->item_name }}" class="brand-image" style="opacity: .8; width :32px; height : 32px">
        <span class="brand-text font-weight-light">{{ $ApplicationSetting->item_name }}</span>
    </a>
    <div class="sidebar">
        <?php
            if(Auth::user()->photo == NULL)
            {
                $photo = "img/profile/male.png";
            } else {
                $photo = Auth::user()->photo;
            }
        ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset($photo) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info my-auto">
                {{ Auth::user()->name }}
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if($c == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>@lang('Dashboard')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('item.index') }}" class="nav-link @if($c == 'item') active @endif ">
                        <i class="fab fa-buffer nav-icon"></i>
                        <p>@lang('Items')</p>
                    </a>
                </li>
                <li class="nav-item has-treeview @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') active @endif">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            @lang('Incomes')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('invoice.index') }}" class="nav-link @if($c == 'invoice') active @endif ">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>@lang('Invoice')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('revenue.index') }}" class="nav-link @if($c == 'revenue') active @endif ">
                                <i class="fas fa-hand-holding-usd nav-icon"></i>
                                <p>@lang('Revenue')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link @if($c == 'customer') active @endif ">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>@lang('Customer')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview @if($c == 'vendor' || $c == 'payment' || $c == 'bill') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'vendor' || $c == 'payment' || $c == 'bill') active @endif">
                        <i class="nav-icon fas fa-minus"></i>
                        <p>
                            @lang('Expenses')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('bill.index') }}" class="nav-link @if($c == 'bill') active @endif ">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>@lang('Bill')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payment.index') }}" class="nav-link @if($c == 'payment') active @endif ">
                                <i class="fab fa-ethereum nav-icon"></i>
                                <p>@lang('Payment')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.index') }}" class="nav-link @if($c == 'vendor') active @endif ">
                                <i class="fas fa-user-minus nav-icon"></i>
                                <p>@lang('Vendor')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @canany(['account-read', 'account-create', 'account-update', 'account-delete', 'account-export', 'transfer-read', 'transfer-create', 'transfer-update', 'transfer-delete', 'transfer-export', 'transaction-read', 'transaction-export'])
                    <li class="nav-item has-treeview @if($c == 'account' || $c == 'transfer' || $c == 'transaction') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'account' || $c == 'transfer' || $c == 'transaction' ) active @endif">
                            <i class="nav-icon fas fa-university"></i>
                            <p>
                                @lang('Banking')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['account-read', 'account-create', 'account-update', 'account-delete', 'account-export'])
                                <li class="nav-item">
                                    <a href="{{ route('account.index') }}" class="nav-link @if($c == 'account') active @endif ">
                                        <i class="fas fa-user-circle nav-icon"></i>
                                        <p>@lang('Accounts')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['transfer-read', 'transfer-create', 'transfer-update', 'transfer-delete', 'transfer-export'])
                                <li class="nav-item">
                                    <a href="{{ route('transfer.index') }}" class="nav-link @if($c == 'transfer') active @endif ">
                                        <i class="fas fa-exchange-alt nav-icon"></i>
                                        <p>@lang('Transfers')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['transaction-read'])
                                <li class="nav-item">
                                    <a href="{{ route('transaction.index') }}" class="nav-link @if($c == 'transaction') active @endif ">
                                        <i class="fas fa-handshake nav-icon"></i>
                                        <p>@lang('Transactions')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['income-report-read', 'expense-report-read', 'tax-report-read', 'profit-loss-report-read', 'income-expense-report-read'])
                    <li class="nav-item has-treeview @if($c == 'report') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'report') active @endif">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                @lang('Reports')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('income-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.income') }}" class="nav-link @if($c == 'report' && $m == 'income') active @endif ">
                                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                                        <p>@lang('Income')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('expense-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.expense') }}" class="nav-link @if($c == 'report' && $m == 'expense') active @endif ">
                                        <i class="fas fa-money-check-alt nav-icon"></i>
                                        <p>@lang('Expense')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('tax-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.tax') }}" class="nav-link @if($c == 'report' && $m == 'tax') active @endif ">
                                        <i class="fas fa-coins nav-icon"></i>
                                        <p>@lang('Tax')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('profit-loss-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.profitAndloss') }}" class="nav-link @if($c == 'report' && $m == 'profitAndloss') active @endif ">
                                        <i class="fas fa-wave-square nav-icon"></i>
                                        <p>@lang('Profit &amp; Loss')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('income-expense-report-read')
                                <li class="nav-item">
                                    <a href="{{ route('report.incomeVsexpense') }}" class="nav-link @if($c == 'report' && $m == 'incomeVsexpense') active @endif ">
                                        <i class="fas fa-columns nav-icon"></i>
                                        <p>@lang('Income VS Expense')</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import', 'currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import','tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                    <li class="nav-item has-treeview @if($c == 'category' || $c == 'currency' || $c == 'tax') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'category' || $c == 'currency' || $c == 'tax') active @endif">
                            <i class="nav-icon fas fa-quote-right"></i>
                            <p>
                                @lang('Types')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import'])
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}" class="nav-link @if($c == 'category') active @endif ">
                                        <i class="fas fa-code-branch nav-icon"></i>
                                        <p>@lang('Category')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import'])
                                <li class="nav-item">
                                    <a href="{{ route('currency.index') }}" class="nav-link @if($c == 'currency') active @endif ">
                                        <i class="fas fa-coins nav-icon"></i>
                                        <p>@lang('Currencies')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                                <li class="nav-item">
                                    <a href="{{ route('tax.index') }}" class="nav-link @if($c == 'tax') active @endif ">
                                        <i class="fas fa-percentage nav-icon"></i>
                                        <p>@lang('Tax rates')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['company-read', 'company-update'])
                    <li class="nav-item">
                        <a href="{{ route('general') }}" class="nav-link @if($c == 'general') active @endif ">
                            <i class="fas fa-align-left nav-icon"></i>
                            <p>@lang('My Company')</p>
                        </a>
                    </li>
                @endcanany
                @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export', 'user-read', 'user-create', 'user-update', 'user-delete', 'user-export', 'offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                    <li class="nav-item has-treeview @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'offline-payment' ) menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'offline-payment' ) active @endif">
                            <i class="nav-icon fa fa-cogs"></i>
                            <p>
                                @lang('Settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export'])
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link @if($c == 'roles') active @endif ">
                                        <i class="fas fa-cube nav-icon"></i>
                                        <p>@lang('Role Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['user-read', 'user-create', 'user-update', 'user-delete', 'user-export'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link @if($c == 'users') active @endif ">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>@lang('User Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @if ($roleName['0'] = "Super Admin")
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
                            @endif
                            @canany(['offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('offline-payment.index') }}" class="nav-link @if($c == 'offline-payment') active @endif ">
                                        <i class="fas fa-money-check nav-icon"></i>
                                        <p>@lang('Offline Payments')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>YPay</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/favicon-16x16.png')}}">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				families: ['Poppins:400']
			}
		});
	</script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    
  {{-- select2 --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="{{asset('/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/sweet-alert.min.css')}}">
  <script src="https://kit.fontawesome.com/10af330120.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <script src="{{asset('/js/adminlte.min.js')}}"></script>
  <script src="{{asset('/js/sweetalert2.min.js')}}"></script>
  <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" type="text/css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" type="text/css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" type="text/css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" type="text/css"/>

  {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
  {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css"/> --}}
  {{-- <script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js" defer></script> --}}
  
  <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

  <link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
  <link rel="stylesheet" href="{{asset('/css/OverlayScrollbars.min.css')}}">
  <script src="{{asset('/js/jquery.overlayScrollbars.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('/css/main.css')}}">


  <style>
    html{
      font-size: 14px !important;
    }
    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }
  </style>

</head>
<body class="sidebar-mini layout-fixed">
  <div class="wrapper">
    <div class="overlay">
      <div class="lds-dual-ring"></div>
    </div>
    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="fas fa-power-off"></i>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>
      </ul>
    </nav>
    {{-- End Navabr --}}

    {{-- Sidebar --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <div class="mt-2 d-flex justify-content-center align-items-center">
        <a href="{{route('dashboard')}}">
          <img src="{{ asset('/images/ypay.png') }}" width="80" alt="YPay Logo" class="brand-image brand-text" style="opacity: .8">
        </a>
      </div>
      <!-- Sidebar -->
      <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
              
            <li class="nav-item {{request()->routeIs('dashboard') ? 'menu-open' : ''}}" >
              <a href="{{route('dashboard')}}" class="nav-link {{request()->routeIs('dashboard') ? 'active' : ''}}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item {{request()->routeIs('investments.index')  || request()->routeIs('dividend.index') || request()->routeIs('redemptions.index') || request()->routeIs('import.csv') ? 'menu-is-opening menu-open' : ''}}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                Operations
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
            @can('view-investment')
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('investments.index')}}" class="nav-link {{request()->routeIs('investments.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>
                  Investments
                </p>
              </a>
            </li>
            </ul>
            @endcan
            @can('view-redemption')
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('redemptions.index')}}" class="nav-link {{request()->routeIs('redemptions.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-hand-holding-usd"></i>
                <p>
                  Redemptions
                </p>
              </a>
            </li>
            </ul>
            @endcan
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('conversions.index')}}" class="nav-link {{request()->routeIs('conversions.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>
                  Conversions
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('dividend.index')}}" class="nav-link {{request()->routeIs('dividend.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>
                  Dividends
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('import.csv')}}" class="nav-link {{request()->routeIs('import.csv') ? 'active' : ''}}">
                <i class="nav-icon fas fa-file-import"></i>
                <p>
                  CSV Import
                </p>
              </a>
            </li>
            </ul>
            </li>
            @canany(['view-customer','view-customer-request','view-customer-newSignup'])
             <li class="nav-item {{request()->routeIs('customer.index')  || request()->routeIs('request.index') || request()->routeIs('newsignup.index') || request()->routeIs('edit_requests.index') ? 'menu-is-opening menu-open' : ''}}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                Customer Management
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
              <!-- @can('view-customer')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('customer.index')}}" class="nav-link {{request()->routeIs('customer.index') ? 'active' : ''}}">
                    <i class="fas fa-user-friends nav-icon"></i>
                    <p>Existing Customers</p>
                  </a>
                </li>
              </ul>
              @endcan -->
              @canany(['view-customer-request','view-customer'])
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('request.index')}}" class="nav-link {{request()->routeIs('request.index') ? 'active' : ''}}">
                    <i class="fas fa-list-alt nav-icon"></i>
                    <p>Customer Profiles</p>
                  </a>
                </li>
              </ul>
              @endcan
              @can('view-customer-newSignup')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('newsignup.index')}}" class="nav-link {{request()->routeIs('newsignup.index') ? 'active' : ''}}">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Signups</p>
                  </a>
                </li>
              </ul>
              @endcan
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('edit_requests.index')}}" class="nav-link {{request()->routeIs('edit_requests.index') ? 'active' : ''}}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                      Edit Profile Requests
                    </p>
                  </a>
                </li>
              </ul>
            </li>
            @endcan
            @canany(['view-user','view-role','change-password'])
            <li class="nav-item {{ request()->routeIs('user.index') || request()->routeIs('role.index')|| request()->routeIs('user.changePassword') ? 'menu-is-opening menu-open' : ''}}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                User Management
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
              @can('view-user')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('user.index') }}" class="nav-link {{request()->routeIs('user.index') ? 'active' : ''}}">
                    <i class="fas fa-list-alt nav-icon"></i>
                    <p>Users</p>
                  </a>
                </li>
              </ul>
              @endcan
              @can('view-role')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('role.index') }}" class="nav-link {{request()->routeIs('role.index') ? 'active' : ''}}">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Roles</p>
                  </a>
                </li>
              </ul>
              @endcan
              @can('change-password')
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('user.changePassword')}}" class="nav-link {{request()->routeIs('user.changePassword') ? 'active' : ''}}">
                      <i class="fas fa-user-friends nav-icon"></i>
                      <p>Change Password</p>
                    </a>
                  </li>
              </ul>
              @endcan
              {{-- @can('permission-list')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('permission.index') }}" class="nav-link {{request()->routeIs('permission.index') ? 'active' : ''}}">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Permission</p>
                  </a>
                </li>
              </ul>
              @endcan --}}
            </li>
            @endcan
            @canany(['view-amc','view-fund','cust-amc-profile'])
            <li class="nav-item {{request()->routeIs('amc.index')  || request()->routeIs('fund.index') || request()->routeIs('funds_data.index') || request()->routeIs('custAmcProfile.index') || request()->routeIs('risk_profile.index') || request()->routeIs('risk_profile_questions.index')|| request()->routeIs('risk_profile_ranks.index') || request()->routeIs('account_types.index')  ? 'menu-is-opening menu-open' : ''}}">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-building"></i>
                <p>
                AMC Management
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
            @can('view-amc')
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc.index')}}" class="nav-link {{request()->routeIs('amc.index') ? 'active' : ''}}">
                <i class="nav-icon far fa-building"></i>
                <p>
                 AMC Profiles
                </p>
              </a>
            </li>
            </ul>
            @endcan
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('account_types.index')}}" class="nav-link {{request()->routeIs('account_types.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-users"></i>
                <p>
                 Account Types
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('risk_profile.index')}}" class="nav-link {{request()->routeIs('risk_profile.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-exclamation-triangle"></i>
                <p>
                 Risk Profiles
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('risk_profile_questions.index')}}" class="nav-link {{request()->routeIs('risk_profile_questions.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-question-circle"></i>
                <p>
                 Risk Profile Questions
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('risk_profile_ranks.index')}}" class="nav-link {{request()->routeIs('risk_profile_ranks.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-star"></i>
                <p>
                 Risk Profile Ranks
                </p>
              </a>
            </li>
            </ul>

            @can('view-fund')
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('fund.index')}}" class="nav-link {{request()->routeIs('fund.index') ? 'active' : ''}}">
                 <i class="nav-icon fas fa-funnel-dollar"></i>
                <p>
                  Funds
                </p>
              </a>
            </li>
            </ul>
            @endcan
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('funds_data.index')}}" class="nav-link {{request()->routeIs('funds_data.index') ? 'active' : ''}}">
                  <i class="nav-icon fas fa-funnel-dollar"></i>
                  <p>
                    Funds Data
                  </p>
                </a>
              </li>
            </ul>
            @can('cust-amc-profile')
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('custAmcProfile.index') }}" class="nav-link {{request()->routeIs('custAmcProfile.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>Customer Verification</p>
              </a>
            </li>
            </ul>
            @endcan
            </li>
            @endcan
            @canany(['view-faq'])
            <li class="nav-item {{request()->routeIs('policies.index') || request()->routeIs('faqs.index') || request()->routeIs('vendors.index') || request()->routeIs('chapter_questions.index') || request()->routeIs('chapters.index') || request()->routeIs('cities.index')|| request()->routeIs('occupations.index')|| request()->routeIs('income_sources.index') || request()->routeIs('banks.index')? 'menu-is-opening menu-open' : ''}}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                  Administration
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <!-- @can('view-policies')
                
                <li class="nav-item">
                  <a href="{{route('policies.index')}}" class="nav-link {{request()->routeIs('policies.index') ? 'active' : ''}}">
                    <i class="fas fa-poll-h nav-icon"></i>
                    <p>Policies</p>
                  </a>
                </li>
                @endcan -->
                 <!-- <li class="nav-item">
                  <a href="{{route('chapters.index')}}" class="nav-link {{request()->routeIs('chapters.index') ? 'active' : ''}}">
                  <i class="nav-icon fa fa-book"></i>
                    <p>YPay Academy Chapters</p>
                  </a>
                </li> -->
                <li class="nav-item">
                  <a href="{{route('chapter_questions.index')}}" class="nav-link {{request()->routeIs('chapter_questions.index') ? 'active' : ''}}">
                  <i class="nav-icon fa fa-question"></i>
                    <p>YPay Chapters Questions</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('banks.index')}}" class="nav-link {{request()->routeIs('banks.index') ? 'active' : ''}}">
                  <i class="nav-icon fa fa-bank"></i>
                    <p>YPay Banks</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('vendors.index')}}" class="nav-link {{request()->routeIs('vendors.index') ? 'active' : ''}}">
                  <i class="nav-icon fa fa-users"></i>
                    <p>OTP Vendors</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('cities.index')}}" class="nav-link {{request()->routeIs('cities.index') ? 'active' : ''}}">
                  <i class="nav-icon fas fa-city"></i>
                    <p>YPay Cities</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('occupations.index')}}" class="nav-link {{request()->routeIs('occupations.index') ? 'active' : ''}}">
                  <i class="nav-icon fas fa-tasks"></i>
                    <p>YPay Occupations</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('income_sources.index')}}" class="nav-link {{request()->routeIs('income_sources.index') ? 'active' : ''}}">
                  <i class="nav-icon fa fa-money"></i>
                    <p>YPay Source of Income</p>
                  </a>
                </li>
                @can('view-faq')
                  
                <li class="nav-item">
                  <a href="{{route('faqs.index')}}" class="nav-link {{request()->routeIs('faqs.index') ? 'active' : ''}}">
                    <i class="far fa-question-circle nav-icon"></i>
                    <p>FAQ's</p>
                  </a>
                </li>
                @endcan
                
              </ul>
            </li>
            @endcan
            <li class="nav-item {{request()->routeIs('amc_sources_of_income.index') ||  request()->routeIs('amc_banks.index')  || request()->routeIs('amc_occupations.index') || request()->routeIs('amc_cities.index') || request()->routeIs('amc_countries.index') ||  request()->routeIs('amc_funds.index') ? 'menu-is-opening menu-open' : ''}}">
            <a href="#" class="nav-link">
                <i class="nav-icon fa fa-key"></i>
                <p>
                API Management
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_sources_of_income.index')}}" class="nav-link {{request()->routeIs('amc_sources_of_income.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-money"></i>
                <p>
                 AMC Sources of Income
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_funds.index')}}" class="nav-link {{request()->routeIs('amc_funds.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-funnel-dollar"></i>
                <p>
                 AMC Funds
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_countries.index')}}" class="nav-link {{request()->routeIs('amc_countries.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-flag"></i>
                <p>
                 AMC Countries
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_banks.index')}}" class="nav-link {{request()->routeIs('amc_banks.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-bank"></i>
                <p>
                 AMC Banks
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_cities.index')}}" class="nav-link {{request()->routeIs('amc_cities.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-city"></i>
                <p>
                 AMC Cities
                </p>
              </a>
            </li>
            </ul>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('amc_occupations.index')}}" class="nav-link {{request()->routeIs('amc_occupations.index') ? 'active' : ''}}">
                <i class="nav-icon fa fa-graduation-cap"></i>
                <p>
                 AMC Occupations
                </p>
              </a>
            </li>
            </ul>
            <li>
            <li class="nav-item {{request()->routeIs('reports.index') ? 'menu-open' : ''}}" >
              <a href="{{route('reports.index')}}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Reports Management
                  <i class="fas fa-angle-right right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('reports.index')}}" class="nav-link {{request()->routeIs('reports.index') ? 'active' : ''}}">
                    <i class="nav-icon fa fa-bank"></i>
                    <p>
                    Unit Statement
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('mis.index')}}" class="nav-link {{request()->routeIs('mis.index') ? 'active' : ''}}">
                    <i class="nav-icon fa fa-file"></i>
                    <p>
                    MIS Report
                    </p>
                  </a>
                </li>
            </ul>
            </li>
            @canany(['view-blog','view-video','send-notification'])
            <li class="nav-item  {{ request()->routeIs('insight.index') || request()->routeIs('insightVideo.index') || request()->routeIs('notification.index')  ? 'menu-is-opening menu-open' : ''}} ">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-lightbulb"></i>
                <p>Marketing</p>
                <i class="fas fa-angle-right right"></i>
              </a>
              @can('view-blog')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('insight.index')}}" class="nav-link {{request()->routeIs('insight.index') ? 'active' : ''}}">
                    <i class="fas fa-user-friends nav-icon"></i>
                    <p>Blogs</p>
                  </a>
                </li>
              </ul>
              @endcan
              @can('view-video')
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('insightVideo.index')}}" class="nav-link {{request()->routeIs('insightVideo.index') ? 'active' : ''}}">
                    <i class="fas fa-list-alt nav-icon"></i>
                    <p>Videos</p>
                  </a>
                </li>
              </ul>
              @endcan
              @can('send-notification')
              <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('notification.index') }}" class="nav-link {{request()->routeIs('notification.index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-paper-plane"></i>

                <p>Notifications</p>
              </a>
            </li>
              </ul>
            @endcan
            </li>
            @endcan
            {{-- <li class="nav-item {{request()->routeIs('insights') ? 'menu-open' : ''}}" >
              <a href="{{route('insights')}}" class="nav-link {{request()->routeIs('insights') ? 'active' : ''}}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li> --}}
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 47.0155%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
      <!-- /.sidebar -->
    </aside>
    {{-- End Sidebar --}}

    {{-- Content --}}
    <div class="content-wrapper">
      @yield('content')
    </div>
    {{-- End Content --}}
  </div>
  @yield('modal')
  {{-- <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script> --}}
  <script>
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on({
        ajaxStart: function(){
            $("body").addClass("loading");
        },
        ajaxStop: function(){
            $("body").removeClass("loading");
        }
    });


    $(".toggle-password").click(function() {
        $(this).toggleClass("fas fa-lock fas fa-unlock");
        var input = $(this).find('.password-field');
        if ($(this).parent().parent().siblings('.password-field').attr("type") == "password") {
          $(this).parent().parent().siblings('.password-field').attr("type", "text");
        } else {
          $(this).parent().parent().siblings('.password-field').attr("type", "password");
        }
    });

    $('.datepicker-from').datepicker({
        uiLibrary: 'bootstrap'
    });
    $('.datepicker-to').datepicker({
        uiLibrary: 'bootstrap'
    });
  </script>

  {{-- firebase configurations --}}
  <script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.6/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.6.6/firebase-analytics.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries
  
    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
      apiKey: "AIzaSyDgfNoIfgDkf1DwuXLo31-q8P_Xv39mWjQ",
    authDomain: "ypay-financials.firebaseapp.com",
    projectId: "ypay-financials",
    storageBucket: "ypay-financials.appspot.com",
    messagingSenderId: "463022349922",
    appId: "1:463022349922:web:8524bbd6bff7dfef0f3b3b",
    measurementId: "G-W0JSWJM6BX"
    };
  
    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
  </script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
  function convertUTCDateToLocalDate(date) {
          var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

          var offset = date.getTimezoneOffset() / 60;
          var hours = date.getHours();

          newDate.setHours(hours - offset);

          return newDate;   
      }
    var firebaseConfig = {
      apiKey: "AIzaSyCQWN3FQTl2p8NZmdoIoqWNLzlDVXk8tbs",
      authDomain: "ypay-5949b.firebaseapp.com",
        databaseURL: 'https://ypay-5949b.firebaseio.com',
        projectId: "ypay-5949b",
      storageBucket: "ypay-5949b.appspot.com",
      messagingSenderId: "1029859183913",
      appId: "1:1029859183913:web:a85c5dfafbfa9355827b38",
      measurementId: "G-XVY0RT1CLY"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    function startFCM() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token stored.');
                    },
                    error: function (error) {
                        alert(error);
                    },
                });
            }).catch(function (error) {
                alert(error);
            });
    }
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>


  @stack('scripts')

</body>
</html>
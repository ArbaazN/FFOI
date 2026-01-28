<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('assets/logo/nav_logo.png') }}" class="img-fluid" alt="logo">
                </span>
            </span>
        </a>

        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a> --}}
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- USERS --}}
        @if(auth()->user()->canAny(['user.view', 'user.create', 'user.edit']))
        <li class="menu-item {{ request()->is('users*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
        @endif

        {{-- CAMPUSES --}}
        @if(auth()->user()->canAny(['campus.view', 'campus.create', 'campus.edit']))
        <li class="menu-item {{ request()->is('campuses*') ? 'active' : '' }}">
            <a href="{{ route('campuses.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-building"></i>
                <div data-i18n="Campuses">Campuses</div>
            </a>
        </li>
        @endif

        {{-- PROGRAMS --}}
        @if(auth()->user()->canAny(['program.view', 'program.create', 'program.edit']))
        <li class="menu-item {{ request()->is('program*') ? 'active' : '' }}">
            <a href="{{ route('programs.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-school"></i>
                <div data-i18n="Programs">Programs</div>
            </a>
        </li>
        @endif

        {{-- BLOGS --}}
        @if(auth()->user()->canAny(['blog.view', 'blog.create', 'blog.edit']))
        <li class="menu-item {{ request()->is('blog*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-brand-blogger"></i>
                <div data-i18n="Blogs">Blogs</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('blog/category') ? 'active' : '' }}">
                    <a href="{{ route('blog.categories.index') }}" class="menu-link">
                        <div data-i18n="Blogs Category">Blogs Category</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('blog') ? 'active' : '' }}">
                    <a href="{{ route('blog.index') }}" class="menu-link">
                        <div data-i18n="All Blogs">All Blogs</div>
                    </a>
                </li>
                @can('blog.create')
                <li class="menu-item {{ request()->is('blog/create') ? 'active' : '' }}">
                    <a href="{{ route('blog.create') }}" class="menu-link">
                        <div data-i18n="Add Blog">Add Blog</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        {{-- PAGES --}}
        @if(auth()->user()->canAny(['page.view', 'page.create', 'page.edit']))
        <li class="menu-item {{ request()->is('admin/pages*') ? 'active' : '' }}">
            <a href="{{ route('pages.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-sitemap"></i>
                <div data-i18n="Pages">Pages</div>
            </a>
        </li>
        @endif

        {{-- SETTINGS --}}
        @if(auth()->user()->canAny(['settings.view', 'settings.update']))
            <li class="menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <a href="{{ route('settings') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-settings-bolt"></i>
                    <div data-i18n="Settings">Settings</div>
                </a>
            </li>
        @endif

        {{-- @if(auth()->user()->canAny(['utm.view', 'utm.update', 'utm.delete']))
            <li class="menu-item {{ request()->is('admin/utm-links*') ? 'active' : '' }}">
                <a href="{{ route('utm-links.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-settings-bolt"></i>
                    <div data-i18n="UTM Generator">UTM Generator</div>
                </a>
            </li>
        @endif --}}
    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>
<!-- resources/views/layouts/sidebar.blade.php -->
@php
$user = auth()->user();
@endphp

<div class="app-sidebar-menu sidebar-style-2">
  <div class="h-100" data-simplebar>
    <div id="sidebar-menu">
      <!-- Logo with conditional dashboard URL -->
      <div class="logo-box text-start ps-1 pt-2">
        <a href="{{ 
            $user && $user->role === 'hospital_manager' 
            ? route('hospital.dashboard') 
            : route('dashboard') 
          }}" 
          class="d-inline-block">
          <span class="logo-lg">
            <img src="{{ asset('assets/images/new_logo_banner.png') }}"
              alt="Wellcare Logo"
              height="65"
              width="180"
              style="cursor:pointer;">
          </span>
        </a>
      </div>

      <ul id="side-menu" class="list-unstyled">
        <li class="menu-title fw-semibold mt-2 mb-2">
          {{ ($user && $user->role === 'hospital_manager') ? 'HOSPITAL MENU' : 'MENU' }}
        </li>

        {{-- ================= ADMIN MENU ================= --}}
        @if($user && $user->role === 'admin')

        <!-- Dashboard -->
        <li>
          <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
            <span>Dashboard</span>
          </a>
        </li>

        {{-- ================= Appointments - Animated Dropdown ================= --}}
        @php
        $isAppointments = request()->routeIs('admin.appointments.*');
        $isAll = request()->routeIs('admin.appointments.index');
        $isQr = request()->routeIs('admin.appointments.hospital_qr');
        @endphp

        <li class="sidebar-group nested-highlight {{ $isAppointments ? 'open' : '' }}">
          <a href="javascript:void(0)" class="group-title d-flex align-items-center {{ $isAppointments ? 'active' : '' }}">
            <i data-feather="clipboard"></i>
            <span>Appointments</span>
          </a>

          <ul class="nested-list list-unstyled">
            <li>
              <a id="sidebarAllAppointmentsBtn"
                href="{{ route('admin.appointments.index') }}"
                data-url="{{ route('admin.appointments.index') }}"
                data-ajax="true"
                class="{{ $isAll ? 'active' : '' }}">
                <span class="dot"></span> All Appointments
              </a>
            </li>
            <li>
              <a id="hospitalQrAppointmentsLink"
                href="{{ route('admin.appointments.hospital_qr') }}"
                data-url="{{ route('admin.appointments.hospital_qr') }}"
                data-ajax="true"
                class="{{ $isQr ? 'active' : '' }}">
                <span class="dot"></span> QR Appointments
              </a>
            </li>
          </ul>
        </li>
        {{-- ================================================================ --}}

        <!-- Packages -->
        <li>
          <a id="sidebarPackageBtn"
            href="{{ route('admin.packages.index') }}"
            data-url="{{ route('admin.packages.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <i data-feather="package"></i>
            <span>Packages</span>
          </a>
        </li>

        <!-- Tests -->
        <li>
          <a id="sidebarLabTestBtn"
            href="{{ route('admin.labtests.index') }}"
            data-url="{{ route('admin.labtests.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.labtests.*') ? 'active' : '' }}">
            <i data-feather="droplet"></i>
            <span>Tests</span>
          </a>
        </li>

        <!-- Coupons -->
        <li>
          <a id="sidebarCouponBtn"
            href="{{ route('admin.coupons.index') }}"
            data-url="{{ route('admin.coupons.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i data-feather="tag"></i>
            <span>Coupons</span>
          </a>
        </li>

        <!-- Banners -->
        <li>
          <a id="sidebarBannerBtn"
            href="{{ route('admin.banners.index') }}"
            data-url="{{ route('admin.banners.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
            <i data-feather="image"></i>
            <span>Manage Banners</span>
          </a>
        </li>

        {{-- ================= Blogs - Animated Dropdown ================= --}}
        @php
        $isBlogsGroup = request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog-categories.*');
        $isBlogsActive = request()->routeIs('admin.blogs.*');
        $isCategoriesActive = request()->routeIs('admin.blog-categories.*');
        @endphp

        <li class="sidebar-group nested-highlight {{ $isBlogsGroup ? 'open' : '' }}">
          <a href="javascript:void(0)" class="group-title d-flex align-items-center {{ $isBlogsGroup ? 'active' : '' }}">
            <i data-feather="edit-3"></i>
            <span>Blog Management</span>
          </a>

          <ul class="nested-list list-unstyled">
            <li>
              <a id="sidebarBlogsBtn"
                href="{{ route('admin.blogs.index') }}"
                data-url="{{ route('admin.blogs.index') }}"
                data-ajax="true"
                class="{{ $isBlogsActive ? 'active' : '' }}">
                <span class="dot"></span> Blogs
              </a>
            </li>
            <li>
              <a id="sidebarBlogCategoriesBtn"
                href="{{ route('admin.blog-categories.index') }}"
                data-url="{{ route('admin.blog-categories.index') }}"
                data-ajax="true"
                class="{{ $isCategoriesActive ? 'active' : '' }}">
                <span class="dot"></span> Blog Categories
              </a>
            </li>
          </ul>
        </li>

        <!-- Custom Pages -->
        <li>
          <a id="sidebarPagesBtn"
            href="{{ route('admin.pages.index') }}"
            data-url="{{ route('admin.pages.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            <i data-feather="file-text"></i>
            <span>Dynamic Pages</span>
          </a>
        </li>

        <!-- Testimonials -->
        <li>
          <a id="sidebarTestimonialsBtn"
            href="{{ route('admin.testimonials.index') }}"
            data-url="{{ route('admin.testimonials.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
            <i data-feather="message-square"></i>
            <span>Testimonials</span>
          </a>
        </li>

        <!-- Hospitals -->
        <li>
          <a id="sidebarHospitalsBtn"
            href="{{ route('admin.hospitals.index') }}"
            data-url="{{ route('admin.hospitals.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
            <i data-feather="compass"></i>
            <span>QR Enrollment </span>
          </a>
        </li>

        <!-- Inquiries -->
        <li>
          <a id="sidebarInquiryBtn"
            href="{{ route('admin.inquiries.index') }}"
            data-url="{{ route('admin.inquiries.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
            <i data-feather="mail"></i>
            <span>Contact Us</span>
          </a>
        </li>

        <!-- Lab Reports -->
        <li>
          <a id="sidebarReportsBtn"
            href="{{ route('admin.reports.index') }}"
            data-url="{{ route('admin.reports.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i data-feather="file-text"></i>
            <span>Lab Reports</span>
          </a>
        </li>

        <!-- FAQs -->
        <li>
          <a id="sidebarFaqsBtn"
            href="{{ route('admin.faqs.index') }}"
            data-url="{{ route('admin.faqs.index') }}"
            data-ajax="true"
            class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
            <i data-feather="help-circle"></i>
            <span>Manage FAQs</span>
          </a>
        </li>

        @endif
        {{-- ============== END ADMIN MENU ============== --}}

        {{-- ============== HOSPITAL MANAGER MENU ============== --}}
        @if($user && $user->role === 'hospital_manager')
        <li>
          <a href="{{ route('hospital.dashboard') }}"
            class="{{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li>
          <a href="{{ route('hospital.appointments.index') }}"
            class="{{ request()->routeIs('hospital.appointments.*') ? 'active' : '' }}">
            <i data-feather="clipboard"></i>
            <span>Appointments</span>
          </a>
        </li>

        <li>
          <a href="{{ route('hospital.profile.show') }}"
            class="{{ request()->routeIs('hospital.profile.*') ? 'active' : '' }}">
            <i data-feather="user"></i>
            <span>Profile</span>
          </a>
        </li>
        @endif
        {{-- ============== END HOSPITAL MANAGER MENU ============== --}}
      </ul>
    </div>
  </div>
</div>

<!-- ===================== SIDEBAR STYLES ===================== -->
<style>
  /* ===== Shared Base Styles ===== */
  .app-sidebar-menu ul li a {
    display: flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none;
    color: #2c3e50;
    padding: .55rem .75rem;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
    transition: all .2s ease;
  }

  /* Animated left border */
  .app-sidebar-menu ul li a::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(180deg, #48be24ff, #5cc8ff);
    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.25s ease;
    border-radius: 0 2px 2px 0;
  }

  /* Hover and active */
  .app-sidebar-menu ul li a:hover::before,
  .app-sidebar-menu ul li a.active::before {
    transform: scaleY(1);
  }

  .app-sidebar-menu ul li a:hover,
  .app-sidebar-menu ul li a.active {
    background: rgba(34, 139, 230, 0.1);
    color: #0b3b58;
    font-weight: 600;
  }

  /* ===== Dropdown (Appointments etc.) ===== */
  .sidebar-style-2 .nested-highlight>.group-title {
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all .2s ease;
  }

  .sidebar-style-2 .nested-highlight>.group-title::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(180deg, #48be24ff, #5cc8ff);
    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.25s ease;
    border-radius: 0 2px 2px 0;
  }

  .sidebar-style-2 .nested-highlight.open>.group-title::before,
  .sidebar-style-2 .nested-highlight>.group-title.active::before {
    transform: scaleY(1);
  }

  .sidebar-style-2 .nested-highlight.open>.group-title,
  .sidebar-style-2 .nested-highlight>.group-title.active {
    background: rgba(34, 139, 230, 0.12);
    color: #0b3b58;
    font-weight: 600;
  }

  /* Dropdown list animation */
  .sidebar-style-2 .nested-highlight ul {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height .3s ease, opacity .3s ease;
  }

  .sidebar-style-2 .nested-highlight.open ul {
    max-height: 300px;
    opacity: 1;
  }

  /* Dropdown arrow rotation */
  .sidebar-style-2 .nested-highlight>.group-title::after {
    content: '▾';
    margin-left: auto;
    transition: transform .3s ease;
  }

  .sidebar-style-2 .nested-highlight.open>.group-title::after {
    transform: rotate(180deg);
  }

  /* ===== Nested list ===== */
  .sidebar-style-2 .nested-list {
    margin-top: .3rem;
    padding-left: 1.2rem;
  }

  .sidebar-style-2 .nested-list a {
    display: flex;
    align-items: center;
    font-weight: 500;
    font-size: .95rem;
    position: relative;
    overflow: hidden;
    transition: all .2s ease;
  }

  .sidebar-style-2 .nested-list a::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(180deg, #48be24ff, #5cc8ff);
    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.25s ease;
    border-radius: 0 2px 2px 0;
  }

  .sidebar-style-2 .nested-list a:hover::before,
  .sidebar-style-2 .nested-list a.active::before {
    transform: scaleY(1);
  }

  .sidebar-style-2 .nested-list a:hover,
  .sidebar-style-2 .nested-list a.active {
    background: rgba(34, 139, 230, 0.12);
    color: #0b3b58;
    font-weight: 600;
  }
</style>

<!-- ===================== SIDEBAR SCRIPT ===================== -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Toggle dropdown
    document.querySelectorAll(".sidebar-style-2 .nested-highlight > .group-title").forEach(btn => {
      btn.addEventListener("click", () => {
        const li = btn.closest(".nested-highlight");
        li.classList.toggle("open");
      });
    });

    // Keep parent open if active child found
    const activeLink = document.querySelector('.nested-list a.active');
    if (activeLink) {
      const parentGroup = activeLink.closest('.nested-highlight');
      if (parentGroup) parentGroup.classList.add('open');
    }
  });
</script>
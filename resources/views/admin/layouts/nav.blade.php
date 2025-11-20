<nav id="sidebar"
    class="bg-[#2C5F5F] w-64 min-h-screen flex flex-col py-6 shadow-lg transition-all duration-300 ease-in-out overflow-hidden">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 mb-8">
        <h1 class="text-white text-lg font-semibold nav-text transition-opacity duration-300">Laiya Admin</h1>
        <button id="toggleSidebarBtn" class="text-white hover:text-yellow-400 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    <!-- Accordion Navigation -->
    <div class="flex-1 px-3 space-y-2 overflow-y-auto" id="admin-accordion">
        <!-- Dashboard & Overview -->
        <div class="accordion-section">
            <button type="button" data-accordion-button data-target="accordion-overview"
                class="accordion-toggle flex items-center justify-between w-full rounded-lg px-2.5 py-1.5 transition">
                <span class="nav-text text-[13px] font-semibold tracking-wide flex items-center gap-2">
                    <i class="fas fa-layer-group"></i>
                    Dashboard & Overview
                </span>
                <i class="fas fa-chevron-down text-xs accordion-arrow rotate-180 transition-transform duration-200"></i>
            </button>
            <div id="accordion-overview" class="accordion-panel mt-1 space-y-0.5">
                <a href="{{ route('admin.new.index') }}" class="accordion-link">
                    <i class="fas fa-plus"></i>
                    <span>New</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="accordion-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="accordion-link">
                    <i class="fas fa-chart-pie"></i>
                    <span>Analytics & Reports</span>
                </a>
            </div>
        </div>

        <!-- Bookings -->
        <div class="accordion-section">
            <button type="button" data-accordion-button data-target="accordion-bookings"
                class="accordion-toggle flex items-center justify-between w-full rounded-lg px-2.5 py-1.5 transition">
                <span class="nav-text text-[13px] font-semibold tracking-wide flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i>
                    Bookings
                </span>
                <i class="fas fa-chevron-down text-xs accordion-arrow rotate-0 transition-transform duration-200"></i>
            </button>
            <div id="accordion-bookings" class="accordion-panel mt-1 space-y-0.5">
                <a href="{{ route('admin.booking.index') }}" class="accordion-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Manage Bookings</span>
                </a>
                <a href="{{ route('admin.reservation.index') }}" class="accordion-link">
                    <i class="fas fa-list-ul"></i>
                    <span>Manage Reservation</span>
                </a>
                <a href="{{ route('admin.checkin.index') }}" class="accordion-link">
                    <i class="fas fa-user-check"></i>
                    <span>Guest & Booking</span>
                </a>
            </div>
        </div>

        <!-- Inventory & Services -->
        <div class="accordion-section">
            <button type="button" data-accordion-button data-target="accordion-inventory"
                class="accordion-toggle flex items-center justify-between w-full rounded-lg px-2.5 py-1.5 transition">
                <span class="nav-text text-[13px] font-semibold tracking-wide flex items-center gap-2">
                    <i class="fas fa-boxes-stacked"></i>
                    Inventory & Services
                </span>
                <i class="fas fa-chevron-down text-xs accordion-arrow rotate-0 transition-transform duration-200"></i>
            </button>
            <div id="accordion-inventory" class="accordion-panel mt-1 space-y-0.5">
                <a href="{{ route('admin.room.index') }}" class="accordion-link">
                    <i class="fas fa-bed"></i>
                    <span>Room</span>
                </a>
                <a href="{{ route('admin.foods.index') }}" class="accordion-link">
                    <i class="fas fa-utensils"></i>
                    <span>Foods</span>
                </a>
                <a href="{{ route('admin.packages.index') }}" class="accordion-link">
                    <i class="fas fa-box"></i>
                    <span>Packages</span>
                </a>
            </div>
        </div>

        <!-- Financials -->
        <div class="accordion-section">
            <button type="button" data-accordion-button data-target="accordion-financials"
                class="accordion-toggle flex items-center justify-between w-full rounded-lg px-2.5 py-1.5 transition">
                <span class="nav-text text-[13px] font-semibold tracking-wide flex items-center gap-2">
                    <i class="fas fa-peso-sign"></i>
                    Financials
                </span>
                <i class="fas fa-chevron-down text-xs accordion-arrow rotate-0 transition-transform duration-200"></i>
            </button>
            <div id="accordion-financials" class="accordion-panel mt-1 space-y-0.5">
                <a href="{{ route('admin.test-payment-ocr') }}" class="accordion-link">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
                <a href="#" class="accordion-link">
                    <i class="fas fa-percentage"></i>
                    <span>Promos & Discounts</span>
                </a>
                <a href="{{ route('admin.pos.index') }}" class="accordion-link">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Point of Sale</span>
                </a>
            </div>
        </div>

        <!-- Documents -->
        <div class="accordion-section">
            <button type="button" data-accordion-button data-target="accordion-documents"
                class="accordion-toggle flex items-center justify-between w-full rounded-lg px-2.5 py-1.5 transition">
                <span class="nav-text text-[13px] font-semibold tracking-wide flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    Documents
                </span>
                <i class="fas fa-chevron-down text-xs accordion-arrow rotate-0 transition-transform duration-200"></i>
            </button>
            <div id="accordion-documents" class="accordion-panel mt-1 space-y-0.5">
                <a href="{{ route('admin.qr.scanner') }}" class="accordion-link">
                    <i class="fas fa-qrcode"></i>
                    <span>Documents & QR</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/20 my-6 mx-6"></div>

    <!-- Footer -->
    <div class="px-6">
        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit"
                class="flex items-center space-x-3 text-white hover:text-yellow-400 transition w-full text-left">
                <i class="fas fa-sign-out-alt text-xl w-6 shrink-0"></i>
                <span class="nav-text text-sm font-medium transition-opacity duration-300">Logout</span>
            </button>
        </form>
    </div>
</nav>

<script>
    
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        const isExpanded = sidebar.classList.contains('w-64');
        const texts = sidebar.querySelectorAll('.nav-text');

        if (isExpanded) {
            // Collapse: fade text out first to avoid jitter, then shrink width
            texts.forEach(text => text.classList.add('opacity-0'));
            // next frame to ensure opacity transition begins before width change
            requestAnimationFrame(() => {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
            });
        } else {
            // Expand: grow width first, then fade text back in after width transition ends
            const onTransitionEnd = (e) => {
                if (e.propertyName === 'width') {
                    texts.forEach(text => text.classList.remove('opacity-0'));
                    sidebar.removeEventListener('transitionend', onTransitionEnd);
                }
            };
            sidebar.addEventListener('transitionend', onTransitionEnd);
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
        }
    });

    // Accordion interactions
    const accordionButtons = Array.from(document.querySelectorAll('[data-accordion-button]'));
    const accordionPanels = Array.from(document.querySelectorAll('.accordion-panel'));

    function openPanel(panel, button, arrow) {
        panel.classList.add('open');
        panel.style.maxHeight = panel.scrollHeight + 'px';
        if (button) button.classList.add('active');
        if (arrow) arrow.classList.add('rotate-180');
    }

    function closePanel(panel, button, arrow) {
        panel.classList.remove('open');
        panel.style.maxHeight = '0px';
        if (button) button.classList.remove('active');
        if (arrow) arrow.classList.remove('rotate-180');
    }

    accordionPanels.forEach((panel, index) => {
        panel.classList.remove('open');
        panel.style.maxHeight = '0px';
        accordionButtons[index]?.classList.remove('active');
        accordionButtons[index]?.querySelector('.accordion-arrow')?.classList.remove('rotate-180');
    });

    accordionButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const targetPanel = accordionPanels[index];
            const arrow = button.querySelector('.accordion-arrow');
            const isOpen = targetPanel.classList.contains('open');

            accordionPanels.forEach((panel, i) => {
                if (i !== index) {
                    closePanel(panel, accordionButtons[i], accordionButtons[i].querySelector(
                        '.accordion-arrow'));
                }
            });

            if (isOpen) {
                closePanel(targetPanel, button, arrow);
            } else {
                openPanel(targetPanel, button, arrow);
            }
        });
    });

    window.addEventListener('resize', () => {
        accordionPanels.forEach((panel) => {
            if (panel.classList.contains('open')) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
            }
        });
    });
</script>

<style>
    /* Smooth transition for sidebar width and text visibility */
    #sidebar {
        transition: width 0.3s ease-in-out;
        will-change: width;
    }

    /* Ensure nav text fades smoothly and doesn't wrap during collapse */
    #sidebar .nav-text {
        transition: opacity 0.2s ease-in-out;
        white-space: nowrap;
        overflow: hidden;
    }

    .accordion-section+.accordion-section {
        margin-top: 0.25rem;
    }

    .accordion-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.6rem;
        border-radius: 7px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .accordion-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    .accordion-toggle {
        color: rgba(255, 255, 255, 0.85);
        background: transparent;
        transition: background 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        border: 1px solid transparent;
    }

    .accordion-toggle:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .accordion-toggle.active {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .accordion-toggle .nav-text i {
        transition: color 0.25s ease;
    }

    .accordion-toggle:hover .nav-text i {
        color: #f4d03f;
    }

    .accordion-panel {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
        transition: max-height 0.4s ease, opacity 0.3s ease;
    }

    .accordion-panel.open {
        opacity: 1;
        pointer-events: auto;
    }
</style>

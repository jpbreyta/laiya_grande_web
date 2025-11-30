<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<nav id="site-nav"
    class="sticky top-0 z-[100] bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-500 font-poppins">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="nav-inner" class="flex justify-between items-center h-16 transition-all duration-500">

            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="group flex items-center gap-3">
                    <div
                        class="w-8 h-8 flex items-center justify-center text-teal-600 group-hover:text-teal-800 transition-colors duration-300">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                            <path d="M2.75 12.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                fill="none" />
                            <path d="M2.75 8.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                fill="none" opacity="0.6" />
                            <path d="M2.75 16.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                fill="none" opacity="0.6" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center">
                        <span
                            class="font-playfair text-xl font-bold text-teal-900 tracking-tight group-hover:text-teal-700 transition-colors">
                            Laiya Grande
                        </span>
                        <span
                            class="text-[9px] font-poppins tracking-[0.2em] text-slate-400 uppercase group-hover:text-teal-500 transition-colors mt-0.5">
                            Beach Resort
                        </span>
                    </div>
                </a>
            </div>

            <div class="hidden xl:flex items-center gap-8 text-sm font-medium text-slate-500 mx-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-teal-700 transition-colors relative group">Home <span
                        class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ url('/gallery') }}" class="hover:text-teal-700 transition-colors relative group">Gallery
                    <span
                        class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ route('contact') }}" class="hover:text-teal-700 transition-colors relative group">Contact
                    <span
                        class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ route('search.index') }}" class="hover:text-teal-700 transition-colors relative group">My
                    Reservations <span
                        class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
            </div>

            <div class="hidden lg:flex items-center gap-6 flex-shrink-0">
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="https://www.facebook.com/laiyagrande/" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i
                            class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="https://www.instagram.com/laiyagrande/" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i
                            class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="https://www.tiktok.com/@laiyagrandebeachresort" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i
                            class="fa-brands fa-tiktok text-lg"></i></a>
                </div>
            </div>

            <div class="lg:hidden flex items-center gap-4 ml-auto">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span x-show="totalCount > 0"
                        class="absolute top-0 right-0 h-3 w-3 rounded-full bg-teal-500 ring-2 ring-white"></span>
                </a>
                <button id="nav-toggle"
                    class="p-2 text-slate-800 hover:text-teal-600 transition-colors focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    // Navigation Logic
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navIcon = navToggle ? navToggle.querySelector('i') : null;

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
            if (navMenu.classList.contains('hidden')) {
                navIcon.classList.remove('fa-xmark');
                navIcon.classList.add('fa-bars');
            } else {
                navIcon.classList.remove('fa-bars');
                navIcon.classList.add('fa-xmark');
            }
        });
    }

    const nav = document.getElementById('site-nav');
    const inner = document.getElementById('nav-inner');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            nav.classList.add('shadow-lg', 'bg-white/98');
            nav.classList.remove('shadow-sm');
            inner.classList.remove('h-16');
            inner.classList.add('h-14');
        } else {
            nav.classList.remove('shadow-lg', 'bg-white/98');
            nav.classList.add('shadow-sm');
            inner.classList.remove('h-14');
            inner.classList.add('h-16');
        }
    });

    function cartDropdown() {
        return {
            open: false,
            cartItems: {},
            items: [],
            totalCount: 0,
            totalPrice: 0,
            bookingId: Math.floor(Math.random() * 9000) + 1000,

            initCart() {
                this.fetchCart();
            },

            fetchCart() {
                fetch('{{ route('cart.details') }}')
                    .then(response => response.json())
                    .then(data => {
                        this.cartItems = data.cart;
                        this.items = Object.values(data.cart);
                        this.totalCount = data.total_count;
                        this.totalPrice = data.total_price;
                    })
                    .catch(error => console.error('Error fetching cart:', error));
            },

            removeFromCart(id) {
                // SWEET ALERT 2 IMPLEMENTATION
                Swal.fire({
                    title: 'Remove Room?',
                    text: "Are you sure you want to remove this item?",
                    icon: 'warning',
                    iconColor: '#f43f5e', // Rose-500
                    showCancelButton: true,
                    confirmButtonColor: '#0f766e', // Teal-700 (Matches your theme)
                    cancelButtonColor: '#94a3b8', // Slate-400
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Keep it',
                    customClass: {
                        popup: 'rounded-xl font-poppins text-sm',
                        title: 'font-playfair font-bold text-slate-800',
                        confirmButton: 'rounded-lg px-4 py-2 text-xs font-bold',
                        cancelButton: 'rounded-lg px-4 py-2 text-xs font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Optimistic UI Update
                        delete this.cartItems[id];
                        this.items = Object.values(this.cartItems);

                        fetch(`/cart/remove/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    id: id
                                })
                            })
                            .then(response => {
                                if (response.ok) {
                                    this.fetchCart();
                                    // Optional Success Toast
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal
                                                .resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Item removed successfully'
                                    })
                                } else {
                                    Swal.fire('Error', 'Could not remove item.', 'error');
                                    this.fetchCart();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.fetchCart();
                            });
                    }
                });
            }
        }
    }
</script>

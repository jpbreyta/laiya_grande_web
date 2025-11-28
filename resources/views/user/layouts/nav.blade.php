<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<nav id="site-nav"
    class="sticky top-0 z-[100] bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-500 font-poppins">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="nav-inner" class="flex justify-between items-center h-16 transition-all duration-500">

            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="group flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center text-teal-600 group-hover:text-teal-800 transition-colors duration-300">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                            <path d="M2.75 12.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                            <path d="M2.75 8.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6" />
                            <path d="M2.75 16.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-playfair text-xl font-bold text-teal-900 tracking-tight group-hover:text-teal-700 transition-colors">
                            Laiya Grande
                        </span>
                        <span class="text-[9px] font-poppins tracking-[0.2em] text-slate-400 uppercase group-hover:text-teal-500 transition-colors mt-0.5">
                            Beach Resort
                        </span>
                    </div>
                </a>
            </div>

            <div class="hidden xl:flex items-center gap-8 text-sm font-medium text-slate-500 mx-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-teal-700 transition-colors relative group">Home <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ url('/rooms') }}" class="hover:text-teal-700 transition-colors relative group">Rooms <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ url('/gallery') }}" class="hover:text-teal-700 transition-colors relative group">Gallery <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ url('/about-us') }}" class="hover:text-teal-700 transition-colors relative group">About Us <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ route('contact') }}" class="hover:text-teal-700 transition-colors relative group">Contact <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
                <a href="{{ route('search.index') }}" class="hover:text-teal-700 transition-colors relative group">My Reservations <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span></a>
            </div>

            <div class="hidden lg:flex items-center gap-6 flex-shrink-0">
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="#" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="#" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="#" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-tiktok text-lg"></i></a>
                    <a href="#" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-solid fa-envelope text-lg"></i></a>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>

                <div class="flex items-center gap-5">

                    <div x-data="cartDropdown()" x-init="initCart()" @cart-updated.window="fetchCart()" class="relative z-50">

                        <button @click="open = !open" @click.outside="open = false" class="relative group p-1 text-slate-500 hover:text-teal-600 transition-colors flex items-center outline-none">
                            <i class="fa-solid fa-cart-shopping text-xl transition-transform group-hover:scale-105"></i>
                            <span x-show="totalCount > 0" x-text="totalCount" x-transition.scale class="absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-teal-600 text-[10px] font-bold text-white shadow-md border border-white"></span>
                        </button>

                        <div x-show="open" x-cloak 
                             class="absolute right-[-14px] top-full mt-4 w-[340px] origin-top-right z-50"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]" style="display: none;">

                            <div class="absolute -top-[6px] right-[18px] w-3 h-3 bg-white transform rotate-45 border-l border-t border-slate-100 z-50"></div>

                            <div class="relative bg-white border border-slate-100 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden font-poppins">

                                <div class="px-5 py-3 bg-white border-b border-slate-50 flex justify-between items-center relative z-10">
                                    <div class="flex flex-col">
                                        <h4 class="font-playfair text-slate-800 font-bold text-base tracking-tight">Your Selection</h4>
                                        <p class="text-[10px] text-slate-400 font-medium tracking-wide mt-0.5">
                                            Order #BOOKING-<span x-text="bookingId"></span>
                                        </p>
                                    </div>
                                    <button @click="open = false" class="text-slate-300 hover:text-slate-500 transition-colors p-1">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>

                                <div class="max-h-[50vh] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent bg-white">
                                    
                                    <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-8 text-center text-slate-400">
                                        <i class="fa-solid fa-basket-shopping text-2xl mb-2 opacity-20"></i>
                                        <p class="text-xs font-medium">Cart is empty.</p>
                                    </div>

                                    <template x-for="(item, id) in cartItems" :key="id">
                                        <div class="relative p-3 border-b border-slate-50 hover:bg-slate-50 transition-colors duration-200">
                                            
                                            <div class="flex gap-3">
                                                <div class="relative w-14 h-14 rounded-md overflow-hidden flex-shrink-0 bg-slate-100 border border-slate-100">
                                                    <img x-show="item.image" :src="item.image" class="w-full h-full object-cover">
                                                    <div x-show="!item.image" class="w-full h-full flex items-center justify-center text-slate-300">
                                                        <i class="fa-solid fa-bed text-lg"></i>
                                                    </div>
                                                </div>

                                                <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                                    <div class="flex justify-between items-start">
                                                        <h5 class="font-playfair font-bold text-slate-800 text-sm leading-tight truncate w-32" x-text="item.room_name"></h5>
                                                        
                                                        <button @click="removeFromCart(id)" class="text-slate-300 hover:text-rose-500 transition-colors text-xs" title="Remove">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </div>

                                                    <div class="flex justify-between items-end mt-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[10px] text-slate-500 font-medium">
                                                                Qty: <span x-text="item.quantity"></span>
                                                            </span>
                                                            <span class="text-[9px] font-bold text-green-600 bg-green-50 px-1 rounded uppercase tracking-wider">
                                                                Instant Confirm
                                                            </span>
                                                        </div>

                                                        <span class="font-bold text-slate-900 text-sm" x-text="'₱' + Number(item.room_price).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="bg-slate-50/50 border-t border-slate-100 p-4" x-show="items.length > 0">
                                    <div class="flex justify-between items-end mb-3">
                                        <div>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</p>
                                            <p class="text-[9px] text-green-600 font-medium leading-none mt-0.5">Incl. taxes & fees</p>
                                        </div>
                                        <span class="text-xl font-bold text-slate-800 font-playfair" x-text="'₱' + Number(totalPrice).toLocaleString()"></span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('cart.index') }}" class="flex justify-center items-center py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-[11px] font-bold hover:bg-slate-50 hover:text-slate-900 transition-all">
                                            View Cart
                                        </a>
                                        <a href="{{ route('cart.index') }}" class="flex justify-center items-center py-2 rounded-lg bg-teal-700 text-white text-[11px] font-bold hover:bg-teal-800 transition-all shadow-md shadow-teal-700/20 active:scale-[0.98]">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('user.rooms.index') }}" class="bg-[#2f4f4f] hover:bg-[#253e3e] text-white px-5 py-2 rounded-full font-medium text-xs transition-colors shadow-lg shadow-teal-900/10 tracking-wide">
                        Book Now
                    </a>
                </div>
            </div>

            <div class="lg:hidden flex items-center gap-4 ml-auto">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span x-show="totalCount > 0" class="absolute top-0 right-0 h-3 w-3 rounded-full bg-teal-500 ring-2 ring-white"></span>
                </a>
                <button id="nav-toggle" class="p-2 text-slate-800 hover:text-teal-600 transition-colors focus:outline-none">
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ id: id })
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
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
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
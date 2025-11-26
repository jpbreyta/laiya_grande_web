@if ($rooms->count())
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Our Rooms</h2>
                <div class="w-24 h-1 bg-teal-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div x-data="{
                current: 0,
                rooms: {{ $rooms->toJson() }},
                next() { if (this.current < this.rooms.length - 3) this.current++ },
                prev() { if (this.current > 0) this.current-- }
            }" class="relative group/carousel">

                <div class="flex -mx-4 overflow-hidden">
                    <template x-for="(room, index) in rooms" :key="index">
                        <div x-show="index >= current && index < current + 3"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="w-full md:w-1/3 px-4 flex-shrink-0">

                            <div
                                class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 overflow-hidden h-full flex flex-col group">
                                <div class="relative overflow-hidden h-64">
                                    <img :src="room.image_url" :alt="room.name"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm">
                                        <span class="text-teal-600 font-bold"
                                            x-text="'₱' + Number(room.price).toLocaleString()"></span>
                                        <span class="text-xs text-slate-500 font-medium">/night</span>
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-slate-800 mb-2" x-text="room.name"></h3>
                                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed"
                                            x-text="room.short_description"></p>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mb-6">
                                        <span x-show="room.has_aircon"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-snowflake mr-1.5 text-teal-500"></i>Aircon
                                        </span>
                                        <span x-show="room.has_private_cr"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-bath mr-1.5 text-teal-500"></i>Private CR
                                        </span>
                                        <span x-show="room.has_kitchen"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-utensils mr-1.5 text-teal-500"></i>Kitchen
                                        </span>
                                        <span x-show="room.has_free_parking"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <i class="fas fa-car mr-1.5 text-teal-500"></i>Parking
                                        </span>
                                        <span x-show="room.no_entrance_fee"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                            <i class="fas fa-check mr-1.5"></i>No Entrance
                                        </span>
                                    </div>

                                    <div class="mt-auto pt-4 border-t border-slate-100">
                                        <a :href="'/rooms/' + room.id"
                                            class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-xl transition-colors duration-200">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button @click="prev()" :class="{ 'opacity-50 cursor-not-allowed': current === 0 }"
                    class="absolute -left-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg text-slate-700 hover:text-teal-600 hover:scale-110 transition-all duration-200 z-10 focus:outline-none hidden md:flex">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <button @click="next()" :class="{ 'opacity-50 cursor-not-allowed': current >= rooms.length - 3 }"
                    class="absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg text-slate-700 hover:text-teal-600 hover:scale-110 transition-all duration-200 z-10 focus:outline-none hidden md:flex">
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>
            </div>
        </div>
    </section>
@endif

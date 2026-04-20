<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="relative h-[60vh] flex items-center overflow-hidden bg-gray-900">
    <div class="absolute inset-0 opacity-40">
        <img src="https://images.unsplash.com/photo-1486006920555-c77dcf18193c?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8Z2FyYWdlfGVufDB8fDB8fHww" class="w-full h-full object-cover" alt="Garage background">
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">
        <div class="max-w-2xl">
            <h4 class="text-red-600 font-bold tracking-[0.3em] uppercase mb-4 animate-fadeUp">Since 2016</h4>
            <h1 class="text-6xl md:text-7xl font-black text-white leading-none uppercase animate-fadeUp delay-500">
                Driven by <span class="text-transparent border-t-2 border-b-2 border-white" style="-webkit-text-stroke: 1px white;">Precision</span>
            </h1>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-16 bg-white" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
</section>

<section class="relative -mt-8 z-20">
    <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 bg-white shadow-2xl rounded-2xl overflow-hidden border-b-4 border-red-600">
            <div class="p-8 text-center border-r border-gray-100">
                <span class="block text-3xl font-bold text-gray-900">10+</span>
                <span class="text-xs uppercase text-gray-500 tracking-widest">Years</span>
            </div>
            <div class="p-8 text-center border-r border-gray-100">
                <span class="block text-3xl font-bold text-gray-900">5k+</span>
                <span class="text-xs uppercase text-gray-500 tracking-widest">Clients</span>
            </div>
            <div class="p-8 text-center border-r border-gray-100">
                <span class="block text-3xl font-bold text-gray-900">15+</span>
                <span class="text-xs uppercase text-gray-500 tracking-widest">Experts</span>
            </div>
            <div class="p-8 text-center">
                <span class="block text-3xl font-bold text-gray-900">100%</span>
                <span class="text-xs uppercase text-gray-500 tracking-widest">Safe</span>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row gap-16 items-center">
            <div class="md:w-1/2 relative">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://www.thedrive.com/wp-content/uploads/2022/08/02/Andrew-Collins-garage.jpg?quality=85&w=1920" class="rounded-lg shadow-lg mt-12 animate-fadeUp" alt="Detailing">
                    <img src="https://images.unsplash.com/photo-1562426509-5044a121aa49" class="rounded-lg shadow-lg animate-fadeUp delay-500" alt="Engine work">
                </div>
                <div class="absolute -z-10 top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl"></div>
            </div>
            <div class="md:w-1/2">
                <h2 class="text-5xl font-bold text-gray-900 mb-8 leading-tight">We Care For Your Car Like <span class="text-red-600">Our Own.</span></h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    AutoCare was founded on a simple principle: Quality should never be compromised. What started in a small Kurunegala workshop has evolved into a symbol of trust for luxury and everyday vehicle owners alike.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-600 flex items-center justify-center text-white rounded-lg shrink-0">✓</div>
                        <div>
                            <h4 class="font-bold text-gray-900">Advanced Tech</h4>
                            <p class="text-sm text-gray-500">We use computer-aided diagnostics for every vehicle.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-900 flex items-center justify-center text-white rounded-lg shrink-0">✓</div>
                        <div>
                            <h4 class="font-bold text-gray-900">Certified Parts</h4>
                            <p class="text-sm text-gray-500">Only genuine spares and high-grade lubricants used.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-slate-950 text-white overflow-hidden relative">

    <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-600/5 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/5 rounded-full blur-[120px]"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-24">
            <h2 class="text-5xl font-black uppercase tracking-tighter italic">
                Our <span class="text-red-600">Evolution</span>
            </h2>
            <div class="w-24 h-1 bg-red-600 mx-auto mt-4"></div>
        </div>

        <div class="relative">
            <div class="absolute left-8 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-red-600 via-red-500/50 to-transparent"></div>

            <div class="relative mb-20 md:mb-32 flex flex-col md:flex-row items-center w-full">
                <div class="absolute left-8 md:left-1/2 md:-translate-x-1/2 w-4 h-4 bg-red-600 rounded-full z-20 shadow-[0_0_20px_rgba(220,38,38,1)] border-4 border-slate-950"></div>

                <div class="ml-16 md:ml-0 md:w-1/2 md:pr-16 md:text-right">
                    <div class="bg-white/5 p-8 rounded-2xl border border-white/10 backdrop-blur-sm hover:border-red-600/50 transition-all duration-500">
                        <h3 class="text-4xl font-black text-red-600 mb-2">2016</h3>
                        <h4 class="text-xl font-bold mb-3">The Garage Foundation</h4>
                        <p class="text-gray-400">The first AutoCare garage opens in Kurunegala, starting with just two service bays and a dream of excellence.</p>
                    </div>
                </div>
                <div class="hidden md:block md:w-1/2"></div>
            </div>

            <div class="relative mb-20 md:mb-32 flex flex-col md:flex-row items-center w-full">
                <div class="absolute left-8 md:left-1/2 md:-translate-x-1/2 w-4 h-4 bg-red-600 rounded-full z-20 shadow-[0_0_20px_rgba(220,38,38,1)] border-4 border-slate-950"></div>

                <div class="hidden md:block md:w-1/2"></div>

                <div class="ml-16 md:ml-0 md:w-1/2 md:pl-16 text-left">
                    <div class="bg-white/5 p-8 rounded-2xl border border-white/10 backdrop-blur-sm hover:border-red-600/50 transition-all duration-500">
                        <h3 class="text-4xl font-black text-red-600 mb-2">2020</h3>
                        <h4 class="text-xl font-bold mb-3">Technological Expansion</h4>
                        <p class="text-gray-400">Expanded to a full-scale service center with 10+ professional bays and advanced diagnostic systems.</p>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col md:flex-row items-center w-full">
                <div class="absolute left-8 md:left-1/2 md:-translate-x-1/2 w-6 h-6 bg-white rounded-full z-20 shadow-[0_0_30px_rgba(255,255,255,1)] border-4 border-red-600"></div>

                <div class="ml-16 md:ml-0 md:w-1/2 md:pr-16 md:text-right">
                    <div class="bg-gradient-to-br from-red-600 to-red-900 p-8 rounded-2xl shadow-2xl">
                        <h3 class="text-4xl font-black text-white mb-2 italic">TODAY</h3>
                        <h4 class="text-xl font-bold mb-3 text-white">Market Leadership</h4>
                        <p class="text-red-100">Trusted by 5000+ customers as the leading specialist for luxury and high-end vehicle care.</p>
                    </div>
                </div>
                <div class="hidden md:block md:w-1/2"></div>
            </div>

        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-black text-gray-900 uppercase">The Crew</h2>
            <p class="text-gray-500 mt-2">Passionate experts ready to serve you.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($team as $member): ?>
                <div class="relative group h-[400px] overflow-hidden rounded-3xl bg-gray-100">
                    <img src="<?= $member['img'] ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700" alt="<?= $member['name'] ?>">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-60 group-hover:opacity-100 transition-all"></div>
                    <div class="absolute bottom-0 left-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                        <h4 class="text-2xl font-bold text-white"><?= $member['name'] ?></h4>
                        <p class="text-red-500 text-sm font-bold uppercase tracking-widest"><?= $member['role'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
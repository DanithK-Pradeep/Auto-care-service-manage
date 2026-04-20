<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<section class="relative h-[35vh] flex items-center justify-center bg-gray-900">
    <div class="absolute inset-0 opacity-40">
        <img src="https://images.wallpaperscraft.com/image/single/car_garage_tuning_139486_5056x3500.jpg" class="w-full h-full object-cover" alt="Contact Us">
    </div>
    <div class="relative z-10 text-center text-white">
        <h1 class="text-5xl font-black uppercase tracking-widest animate-fadeUp">Get In Touch</h1>
        <div class="w-20 h-1 bg-red-600 mx-auto mt-4 animate-fadeUp delay-500"></div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-16">
            
            <div class="lg:col-span-1 space-y-8 animate-fadeUp">
                <h2 class="text-3xl font-bold text-gray-900">Contact Information</h2>
                <p class="text-gray-500">We are always ready to assist you. Contact us through any of these channels.</p>

                <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-2xl hover:bg-red-50 transition-colors group">
                    <div class="w-14 h-14 bg-red-600 flex items-center justify-center text-white rounded-xl text-2xl group-hover:scale-110 transition-transform">📞</div>
                    <div>
                        <h4 class="font-bold text-gray-900">Call Us</h4>
                        <p class="text-gray-600">+94 112 456 777</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-2xl hover:bg-red-50 transition-colors group">
                    <div class="w-14 h-14 bg-blue-900 flex items-center justify-center text-white rounded-xl text-2xl group-hover:scale-110 transition-transform">✉️</div>
                    <div>
                        <h4 class="font-bold text-gray-900">Email Us</h4>
                        <p class="text-gray-600">info@autocare.lk</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-2xl hover:bg-red-50 transition-colors group">
                    <div class="w-14 h-14 bg-gray-800 flex items-center justify-center text-white rounded-xl text-2xl group-hover:scale-110 transition-transform">📍</div>
                    <div>
                        <h4 class="font-bold text-gray-900">Location</h4>
                        <p class="text-gray-600">Main Street, Kurunegala</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-10 rounded-3xl shadow-2xl border border-gray-100 animate-carSlide">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Send Us a Message</h2>
                
                <form id="contactPageForm" class="grid md:grid-cols-2 gap-8">
                    <?= csrf_field() ?>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase">First Name</label>
                        <input type="text" name="fname" class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="John">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase">Last Name</label>
                        <input type="text" name="lname" class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase">Email Address</label>
                        <input type="email" name="email" class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="john@example.com">
                    </div>
                    <div class=" space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase">Phone Number</label>
                        <input type="tel" name="phone" class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="+94 112 456 777">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 uppercase">Message</label>
                        <textarea name="message" rows="5" class="w-full bg-gray-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="How can we help you?"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-red-600 text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<section class="h-[500px] w-full bg-gray-200 overflow-hidden relative grayscale hover:grayscale-0 transition-all duration-700">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31613.0645161!2d80.3475!3d7.4875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae339660c6d70a7%3A0x6d5e7a911a367468!2sKurunegala!5e0!3m2!1sen!2slk!4v1713300000000!5m2!1sen!2slk" 
        width="100%" 
        height="100%" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</section>

<?= $this->endSection() ?>
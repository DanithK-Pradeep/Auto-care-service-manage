<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- HERO SECTION -->
<section class="relative h-screen overflow-hidden flex items-center">

  <!-- BACKGROUND IMAGE -->
  <div
    class="absolute inset-0 bg-center bg-cover animate-heroBg"
    style="background-image: url('https://images.unsplash.com/photo-1502877338535-766e1452684a');">
  </div>

  <!-- DARK OVERLAY -->
  <div class="absolute inset-0 bg-black/60"></div>

  <!-- CONTENT GRID -->
  <div class="items-center max-w-7xl mx-auto px-6 relative z-10 grid md:grid-cols-2 gap-8">

    <!-- TEXT -->
    <div class="text-white max-w-6xl">
      <h1 id="hero-title" class="text-5xl md:text-5xl font-bold leading-tight tracking-wide mb-2 text-white min-h-[120px]"></h1>

      <p class="animate-fadeUp delay-2000 text-gray-200 text-lg mb-8">
        Book your vehicle service online. Fast, reliable, and affordable care
        for your car and bike.
      </p>

      <a href="#book" class="border-2 bg-red-600 border-red-600 text-white px-8 py-3 rounded-lg hover:bg-transparent hover:text-white transition-all duration-300 animate-fadeUp delay-2500 font-bold shadow-lg">
        Book Service Now
      </a>
    </div>



  </div>
</section>


<!-- SERVICES -->
<section class="py-16 bg-white-50">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Heading -->
    <div class="text-center mb-14">
      <h3 class="text-4xl font-bold text-blue-900">Our Services</h3>
      <div class="w-20 h-1 bg-red-600 mx-auto mt-4"></div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

      <?php

      use CodeIgniter\I18n\Time;

      foreach ($services as $service): ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden group
                    transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">

          <img
            src="<?= esc($service['image']) ?>"
            alt="<?= esc($service['title']) ?>"
            class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">

          <div class="p-6">
            <h4 class="text-xl font-bold text-gray-900">
              <?= esc($service['title']) ?>
            </h4>

            <div class="w-12 h-1 bg-red-600 mt-2 mb-4"></div>

            <p class="text-gray-600 mb-6">
              <?= esc($service['desc']) ?>
            </p>

            <button onclick='openServiceModal(<?= json_encode($service) ?>)'
              class="inline-block bg-red-600 text-white px-6 py-2 rounded-md font-medium transition-all duration-300 hover:bg-red-700 hover:translate-x-1 cursor-pointer">
              Read More
            </button>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<div id="serviceDetailModal" class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
    <img id="modalServiceImage" src="" class="w-full h-48 object-cover" alt="Service Image">

    <div class="p-8">
      <h2 id="modalServiceTitle" class="text-2xl font-bold text-gray-900 mb-4"></h2>
      <div id="modalServiceDescription" class="text-gray-600 leading-relaxed space-y-4">
      </div>

      <div class="mt-8 flex justify-end gap-3">
        <button onclick="closeServiceModal()" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">
          Close
        </button>
        <a href="#book"
          onclick="closeServiceModal()"
          class="px-6 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md">
          Book Now
        </a>
      </div>
    </div>
  </div>
</div>


<!-- WHY CHOOSE US -->
<section class="py-10 bg-white">
  <div class=" h-1 bg-red-600 mb-8"></div>
  <div class="max-w-7xl mx-auto px-6">

    <!-- Heading -->

    <div class="text-center mb-14">
      <h2 class="text-4xl font-bold text-blue-900">
        Why Choose Us
      </h2>
      <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
        We provide high-quality vehicle services with experienced technicians,
        modern tools, and complete customer satisfaction.
      </p>
      <div class="w-20 h-1 bg-red-600 mx-auto mt-6"></div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

      <?php foreach ($whyChooseUs as $item): ?>
        <div class="text-center p-6 rounded-xl shadow-md
                    transition hover:-translate-y-2 hover:shadow-xl">

          <div class="text-5xl mb-4">
            <?= esc($item['icon']) ?>
          </div>

          <h4 class="text-xl font-bold text-gray-900 mb-3">
            <?= esc($item['title']) ?>
          </h4>

          <p class="text-gray-600">
            <?= esc($item['desc']) ?>
          </p>

        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- COUNTER SECTION -->
<section class="py-20 bg-blue-900 text-white">
  <div class="max-w-7xl mx-auto px-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 text-center">

      <?php foreach ($counters as $counter): ?>
        <div>
          <h3
            class="text-5xl font-bold counter"
            data-target="<?= esc($counter['value']) ?>"
            data-suffix="<?= esc($counter['suffix']) ?>">
            0
          </h3>

          <p class="mt-3 text-lg text-blue-200">
            <?= esc($counter['label']) ?>
          </p>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- BOOK SERVICE -->
<section id="book" class=" bg-gray-100 ">
  <div class="grid lg:grid-cols-2 max-w-7xl mx-auto gap-12 items-start py-20">

    <!-- Heading -->
    <div class="text-center lg:text-center mb-14 mt-0 lg:mb-0">
      <div>
        <h2 class="text-4xl font-bold text-blue-900">
          Book a Service
        </h2>
        <p class="text-gray-600 mt-4">
          Fill in the form below and we'll take care of your vehicle.

        </p>
        <div class="w-20 h-1 bg-red-600 mx-auto mt-6 "></div>
      </div>

      <!-- Image Box -->
      <div class="mt-8 rounded-xl overflow-hidden shadow-lg transition duration-300 hover:scale-105" id="image-box">
        <img
          src="https://img.freepik.com/free-photo/car-repair-maintenance-theme-mechanic-uniform-working-auto-service_627829-3918.jpg?t=st=1768701484~exp=1768705084~hmac=762c3468e52b9903d66bd7f9fa7b6b7f69092056da3a5b3511320a7aa71076c5"
          alt="Car Service"
          class="w-full h-auto object-cover transition-all duration-500 opacity-100">
      </div>

      <div class="mt-6 text-gray-600 border-l-4 border-red-600 pl-4">
        Our easy-to-use booking system ensures your vehicle gets the care it needs
        at a time that suits you. Experience hassle-free service scheduling today!
      </div>

      <div class="mt-6 text-black-600 border-l-4 border-red-600 pl-4">
        <div class="font-bold">Need Assistance?</div>
        Call us at <a href="tel:+94771234567" class="text-red-600 hover:underline">+94 77 123 4567</a>

      </div>


    </div>


    <!-- Form -->
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <form id="bookingForm">

        <?= csrf_field() ?>

        <!-- Name -->
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">Full Name</label>
          <input type="text" name="name" placeholder="Enter your name"
            class="w-full border border-gray-300 rounded-md px-4 py-3">
          <p class="text-red-600 text-sm mt-1 hidden" data-error="name"></p>
        </div>

        <!-- Phone -->
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
          <input type="tel" name="phone" placeholder="Enter phone number"
            class="w-full border border-gray-300 rounded-md px-4 py-3">
          <p class="text-red-600 text-sm mt-1 hidden" data-error="phone"></p>
        </div>

        <!-- Service -->
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">Select Service</label>
          <select name="service"
            class="w-full border border-gray-300 rounded-md px-4 py-3">
            <option value="">-- Select Service --</option>
            <?php foreach ($servicesList as $key => $service): ?>
              <option value="<?= esc($key) ?>"><?= esc($service) ?></option>
            <?php endforeach; ?>
          </select>
          <p class="text-red-600 text-sm mt-1 hidden" data-error="service"></p>
        </div>

        <!-- Vehicle Model -->
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">Vehicle Model</label>
          <input type="text" name="vehicle_model"
            class="w-full border border-gray-300 rounded-md px-4 py-3">
          <p class="text-red-600 text-sm mt-1 hidden" data-error="vehicle_model"></p>
        </div>

        <!-- Message -->
        <div class="mb-6">
          <label class="block text-gray-700 font-medium mb-2">Message</label>
          <textarea name="message" rows="4"
            class="w-full border border-gray-300 rounded-md px-4 py-3"></textarea>
        </div>

        <!-- Date -->
        <div class="mb-8">
          <label class="block text-gray-700 font-medium mb-2">Preferred Date</label>
          <input type="date" name="booking_date"
            class="w-full border border-gray-300 rounded-md px-4 py-3">
          <p class="text-red-600 text-sm mt-1 hidden" data-error="booking_date"></p>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full bg-red-600 text-white py-3 rounded-md font-semibold hover:bg-red-700">
          Book Service
        </button>




      </form>
    </div>
  </div>
</section>

<section class="animated-bg-section py-8 border-t border-b border-gray-50 ">
  <div class="max-w-7xl mx-auto px-6 mb-6">
    <h2 class="text-4xl font-light text-gray-800 tracking-wider uppercase text-center">Our Trusted Partners</h2>
    <div class="w-32 h-0.5 bg-red-600 mt-4 mx-auto"></div>
  </div>

  <div class="brand-marquee">
    <div class="marquee-track">
      <?php
      $partnerLogos = [
        'https://images.seeklogo.com/logo-png/15/1/volkswagen-logo-png_seeklogo-150527.png',
        'https://images.seeklogo.com/logo-png/9/1/mitsubishi-logo-png_seeklogo-93525.png',
        'https://images.seeklogo.com/logo-png/5/1/ford-logo-png_seeklogo-56575.png',
        'https://images.seeklogo.com/logo-png/1/1/audi-logo-png_seeklogo-13445.png',
        'https://images.seeklogo.com/logo-png/14/1/toyota-logo-png_seeklogo-141406.png',
        'https://images.seeklogo.com/logo-png/23/1/bmw-logo-png_seeklogo-239558.png',
        'https://images.seeklogo.com/logo-png/27/1/bmw-m-logo-png_seeklogo-278503.png',
        'https://images.seeklogo.com/logo-png/16/1/porsche-logo-png_seeklogo-168544.png',
        'https://images.seeklogo.com/logo-png/7/1/kawasaki-logo-png_seeklogo-77593.png'
      ];

      // Seamless loop එක සඳහා සෙට් එක 3 පාරක් ලූප් කරමු
      for ($i = 0; $i < 3; $i++):
        foreach ($partnerLogos as $url): ?>
          <div class="logo-item">
            <img src="<?= $url ?>" alt="Vehicle Brand" loading="eager">
          </div>
      <?php endforeach;
      endfor; ?>
    </div>
  </div>
</section>

<script>
  // --- 1. Variables Declare කිරීම ---
  const titleText = "Premium Auto Care for Your Vehicle";
  const titleElement = document.getElementById('hero-title');
  const heroDesc = document.querySelector('.animate-fadeUp.delay-700');
  const heroBtn = document.querySelector('.animate-fadeUp.mt-6');
  let textPos = 0;

  // --- 2. Typing Function ---
  function typeTitle() {
    if (titleElement && textPos < titleText.length) {
      titleElement.innerHTML += titleText.charAt(textPos);
      textPos++;
      setTimeout(typeTitle, 80); // Typing speed
    } else {
      // Typing එක ඉවර වුණාම විතරක් යට තියෙන පේළි පෙන්වන්න (Optional logic)
      if (heroDesc) heroDesc.style.opacity = "1";
      if (heroBtn) heroBtn.style.opacity = "1";
    }
  }

  // --- 3. Counters Logic (මම මේක තවත් smooth කළා) ---
  function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target');
      const suffix = counter.getAttribute('data-suffix') || '';
      let current = 0;
      const increment = target / 50;

      const update = () => {
        if (current < target) {
          current += increment;
          counter.innerText = Math.ceil(current) + suffix;
          requestAnimationFrame(update);
        } else {
          counter.innerText = target + suffix;
        }
      };
      update();
    });
  }

  // --- 4. Initialize Everything ---
  window.addEventListener('DOMContentLoaded', () => {
    // 1. Typing effect පටන් ගන්න
    if (titleElement) {
      titleElement.innerHTML = ""; // කලින් තිබූ ඕනෑම දෙයක් අයින් කරන්න
      typeTitle();
    }

    // 2. Counters පටන් ගන්න (Scroll කරලා එනකොට විතරක්)
    const counterSec = document.querySelector('.counter')?.closest('section');
    if (counterSec) {
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          animateCounters();
          observer.disconnect();
        }
      }, {
        threshold: 0.5
      });
      observer.observe(counterSec);
    }
  });

  // --- 5. Modal Logic ---
  function openServiceModal(service) {
    const modal = document.getElementById('serviceDetailModal');
    document.getElementById('modalServiceTitle').innerText = service.title;
    document.getElementById('modalServiceDescription').innerHTML = service.long_description;
    document.getElementById('modalServiceImage').src = service.image;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeServiceModal() {
    const modal = document.getElementById('serviceDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }


  // --- 1. Service Modal Logic ---
  function openServiceModal(service) {
    const modal = document.getElementById('serviceDetailModal');
    document.getElementById('modalServiceTitle').innerText = service.title;
    // මෙහිදී 'long_description' හෝ 'desc' භාවිතා කළ හැක
    document.getElementById('modalServiceDescription').innerHTML = service.long_description;
    document.getElementById('modalServiceImage').src = service.image;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeServiceModal() {
    const modal = document.getElementById('serviceDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  // --- 2. Hero Title Typing Animation ---
  const text = "Premium Auto Care for Your Vehicle";
  const title = document.getElementById('hero-title');
  let index = 0;

  function typeTitle() {
    if (index < text.length) {
      title.innerHTML += text.charAt(index);
      index++;
      setTimeout(typeTitle, 100);
    }
  }

  // --- 3. Counters Animation ---
  function startCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target');
      const suffix = counter.getAttribute('data-suffix');
      const updateCount = () => {
        const count = +counter.innerText.replace(suffix, '');
        const speed = target / 100;
        if (count < target) {
          counter.innerText = Math.ceil(count + speed) + suffix;
          setTimeout(updateCount, 20);
        } else {
          counter.innerText = target + suffix;
        }
      };
      updateCount();
    });
  }

  // Initialize on Load
  window.onload = () => {
    typeTitle();
    startCounters();
  };
</script>





<?= $this->endSection() ?>
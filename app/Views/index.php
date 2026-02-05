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
    <div class="text-white max-w-xl">
      <h1
        id="hero-title"
        class="text-5xl md:text-6xl font-bold leading-tight tracking-wide mb-6 text-white">
      </h1>


      <p class="opacity-0 translate-y-6 animate-fadeUp delay-700">
        Book your vehicle service online. Fast, reliable, and affordable care
        for your car and bike.
      </p>

      <button
        class="mt-6 border-2 bg-black border-black text-white px-6 py-2 rounded hover:bg-transparent hover:text-white transition-all duration-300 animate-fadeUp">
        Book Service
      </button>
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

            <a href="<?= site_url('services/' . $service['slug']) ?>"
              class="inline-block bg-red-600 text-white px-6 py-2 rounded-md
                      font-medium transition-all duration-300
                      hover:bg-red-700 hover:translate-x-1">
              Read More
            </a>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>



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







<?= $this->endSection() ?>
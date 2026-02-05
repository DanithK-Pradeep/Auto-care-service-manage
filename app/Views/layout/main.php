<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AutoCare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes heroBg {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.1);
            }
        }

        .animate-heroBg {
            animation: heroBg 12s ease-in-out forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeUp {
            animation: fadeUp 1s ease forwards;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        @keyframes carSlide {
            from {
                opacity: 0;
                transform: translateX(80px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-carSlide {
            animation: carSlide 1.2s ease forwards;
        }

        @keyframes wordFade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <header
        id="navbar"
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-transparent">

        <nav class=" mx-auto pl-12 py-4 flex  justify-between text-white">

            <!-- LOGO -->

            <a href="<?= site_url('/') ?>" class=" flex items-center space-x-2">
                <img src="<?= base_url('assets/images/logo.png') ?>"
                    alt="AutoCare Logo"
                    class="h-10 w-auto" id="logo-img">
                <span class="text-2xl font-bold text-white-900 navbar-text">
                    AutoCare
                </span>
            </a>


            <!-- DESKTOP MENU -->
            <ul class="hidden md:flex items-center pr-2 space-x-8 font-medium navbar-text ">

                <?php foreach ($navItems as $item): ?>
                    <?php $isActive = str_contains($currentUrl, $item['match']); ?>

                    <li class="relative group">

                        <!-- MAIN LINK -->
                        <a href="<?= esc($item['url']) ?>"
                            class="<?= $item['button']
                                        ? 'bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-700 inline-flex items-center'
                                        : ($isActive ? 'text-red-600 font-bold' : 'text-white-700 hover:text-red-600') ?>">

                            <?php if (($item['match'] ?? '') === 'login'): ?>
                                <!-- Login icon only -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                                </svg>
                            <?php else: ?>
                                <?= esc($item['label']) ?>

                                <?php if (isset($item['dropdown'])): ?>
                                    <span class="ml-1">▾</span>
                                <?php endif; ?>
                            <?php endif; ?>

                        </a>



                        <!-- DROPDOWN -->
                        <?php if (isset($item['dropdown'])): ?>
                            <ul class="absolute left-0 mt-3 w-48 bg-white shadow-lg rounded-md
                                               opacity-0 invisible group-hover:opacity-100
                                                group-hover:visible transition-all duration-200">

                                <?php foreach ($item['dropdown'] as $drop): ?>
                                    <li>
                                        <a href="<?= esc($drop['url']) ?>"
                                            class="block px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600">
                                            <?= esc($drop['label']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>

            </ul>



            <!-- MOBILE BUTTON -->
            <button id="menu-btn" class="md:hidden text-3xl text-gray-700">
                ☰
            </button>
        </nav>

        <!-- MOBILE MENU -->
        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md">
            <ul class="flex flex-col px-6 py-4 space-y-4 font-medium">

                <?php foreach ($navItems as $index => $item): ?>
                    <?php $isActive = str_contains($currentUrl, $item['match']); ?>

                    <li>
                        <?php if (isset($item['dropdown'])): ?>

                            <!-- MOBILE DROPDOWN BUTTON -->
                            <button
                                class="w-full flex justify-between items-center text-left
                 <?= $isActive ? 'text-red-600 font-bold' : 'text-gray-700' ?>"
                                onclick="toggleDropdown(<?= $index ?>)">
                                <?= esc($item['label']) ?>
                                <span>▾</span>
                            </button>

                            <!-- MOBILE DROPDOWN LIST -->
                            <ul id="dropdown-<?= $index ?>" class="hidden mt-2 ml-4 space-y-2">
                                <?php foreach ($item['dropdown'] as $drop): ?>
                                    <li>
                                        <a href="<?= esc($drop['url']) ?>"
                                            class="block text-gray-600 hover:text-red-600">
                                            <?= esc($drop['label']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                        <?php else: ?>

                            <a href="<?= esc($item['url']) ?>"
                                class="<?= $item['button']
                                            ? 'block bg-red-600 text-white px-5 py-2 rounded-md text-center'
                                            : ($isActive
                                                ? 'block text-red-600 font-bold'
                                                : 'block text-gray-700') ?>">
                                <?= esc($item['label']) ?>
                            </a>

                        <?php endif; ?>
                    </li>

                <?php endforeach; ?>

            </ul>


        </div>
        <div class="h-0.5 bg-white navbar-spacer"></div> <!-- Spacer for fixed navbar -->
    </header>


    <!-- PAGE CONTENT -->
    <?= $this->renderSection('content') ?>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300 ">
        <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
            <div>
                <h2 class="text-xl font-bold text-white mb-2">AutoCare</h2>
                <p>Your trusted vehicle service partner.</p>
            </div>

            <div>
                <h3 class="font-semibold text-white mb-2">Quick Links</h3>
                <ul class="space-y-1">
                    <li>Home</li>
                    <li>Services</li>
                    <li>Contact</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-white mb-2">Contact</h3>
                <p>📍 Kurunegala, Srilanka</p>
                <p>📞 +94 112 456 777 </p>
            </div>
        </div>

        <div class="text-center py-4 bg-black text-gray-400">
            © 2026 AutoCare. All rights reserved.
        </div>
    </footer>
    <script>
        /* NAVBAR SCROLL EFFECT */
        const navbar = document.getElementById('navbar');
        const navTexts = document.querySelectorAll('.navbar-text');
        const navSpacer = document.querySelector('.navbar-spacer');
        const logoImg = document.getElementById('logo-img');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                // After scroll
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-white', 'shadow-md');


                navSpacer.classList.remove('bg-white');
                navSpacer.classList.add('bg-gray-900');

                logoImg.classList.remove('bg-transparent');
                logoImg.classList.add('bg-black');

                navTexts.forEach(el => {
                    el.classList.remove('text-white');
                    el.classList.add('text-gray-800');


                });

            } else {
                // At top
                navbar.classList.add('bg-transparent');
                navbar.classList.remove('bg-white', 'shadow-md');
                navSpacer.classList.add('bg-white');
                navSpacer.classList.remove('bg-gray-900');
                logoImg.classList.add('bg-transparent');
                logoImg.classList.remove('bg-black');

                navTexts.forEach(el => {
                    el.classList.add('text-white');
                    el.classList.remove('text-gray-800');

                });
            }
        });


        /* MOBILE MENU */
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        function toggleDropdown(index) {
            const dropdown = document.getElementById('dropdown-' + index);
            dropdown.classList.toggle('hidden');
        }


        /* HERO ANIMATION */
        const text = "Reliable Car Service";
        const title = document.getElementById("hero-title");

        text.split("  ").forEach((word, index) => {
            const span = document.createElement("span");

            span.textContent = word + " ";
            span.style.opacity = 0;
            span.style.display = "inline-block";
            span.style.transform = "translateY(20px)";
            span.style.animation = "wordFade 0.6s ease forwards";
            span.style.animationDelay = `${index * 0.25}s`;

            title.appendChild(span);
        });

        /* COUNTER ANIMATION */
        const counters = document.querySelectorAll('.counter');

        const counterObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.dataset.target;
                    const suffix = counter.dataset.suffix || '';
                    let count = 0;

                    const speed = 100; // animation speed

                    const updateCount = () => {
                        const increment = target / speed;

                        if (count < target) {
                            count += increment;
                            counter.innerText = Math.ceil(count);
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.innerText = target + suffix;
                        }
                    };

                    updateCount();
                    counterObserver.unobserve(counter);
                }
            });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });

        /*image shuffle*/
        const imageBox = document.getElementById('image-box');
        const imageBoxImg = imageBox.querySelector('img');
        const images = [
            'https://img.freepik.com/free-photo/car-repair-maintenance-theme-mechanic-uniform-working-auto-service_627829-3918.jpg?t=st=1768701484~exp=1768705084~hmac=762c3468e52b9903d66bd7f9fa7b6b7f69092056da3a5b3511320a7aa71076c5',
            'https://img.freepik.com/free-photo/top-view-woman-repairing-car_23-2150171259.jpg?t=st=1768702771~exp=1768706371~hmac=257722d2e12a77f99f515d749fc34700802678af49ac6ea061c2da362931c261',
            'https://img.freepik.com/free-photo/mechanic-hand-checking-fixing-broken-car-car-service-garage_146671-19613.jpg?t=st=1768702829~exp=1768706429~hmac=43120529763f7e853859a69220ad637037a4f00e3b724f2d12cc0d531c0069be',
            'https://img.freepik.com/free-photo/auto-mechanic-working-garage-repair-service_146671-19601.jpg?t=st=1768702897~exp=1768706497~hmac=cd28b0731a75a6c487108eaebc1a96f912963076d6898fcce550232bff6c176c',

        ];

        let currentIndex = 0;
        setInterval(() => {
            // Fade out and slide right
            imageBoxImg.style.opacity = '0';
            imageBoxImg.style.transform = 'translateX(20px)';

            setTimeout(() => {
                currentIndex = (currentIndex + 1) % images.length;
                imageBoxImg.src = images[currentIndex];

                // Fade in with slide from right
                imageBoxImg.style.opacity = '1';
                imageBoxImg.style.transform = 'translateX(0)';

            }, 500);
        }, 3000);

        /* BOOKING FORM SUBMISSION */

        const form = document.getElementById('bookingForm');

        if (!form) {
            console.error('Form not found');
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // stop page reload
            console.log('FORM SUBMITTED');

            // Clear old errors
            document.querySelectorAll('[data-error]').forEach(el => {
                el.classList.add('hidden');
                el.innerText = '';
            });

            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500');
            });

            const formData = new FormData(form);

            try {
                const response = await fetch("<?= site_url('book-service') ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();
                console.log(result);

                // Update CSRF token 
                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                if (csrfInput && result.csrf) {
                    csrfInput.value = result.csrf;
                }


                if (result.status === 'error') {
                    Object.keys(result.errors).forEach(field => {
                        const errorEl = document.querySelector(`[data-error="${field}"]`);
                        const inputEl = document.querySelector(`[name="${field}"]`);

                        if (errorEl) {
                            errorEl.innerText = result.errors[field];
                            errorEl.classList.remove('hidden');
                        }

                        if (inputEl) {
                            inputEl.classList.add('border-red-500');
                        }
                    });
                }

                if (result.status === 'success') {
                    showSuccessPopup(result.message);
                    form.reset();
                }

            } catch (error) {
                console.error('AJAX ERROR:', error);
            }
        });

        /* SUCCESS POPUP */

        function showSuccessPopup(message) {
            document.getElementById('successMessage').innerText = message;
            document.getElementById('successPopup').classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('successPopup').classList.add('hidden');
        }
    </script>

    <!-- SUCCESS POPUP -->
    <div id="successPopup"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div class="bg-white rounded-xl p-8 text-center max-w-sm w-full">
            <h3 class="text-2xl font-bold text-green-600 mb-4">Success</h3>

            <p id="successMessage" class="mb-6 text-gray-700"></p>

            <button
                type="button"
                onclick="closePopup()"
                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                OK
            </button>
        </div>
    </div>







    <style>
        @keyframes letterFade {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>


    <script src="<?= base_url('assets/js/ajax-handler.js') ?>"></script>

</body>

</html>
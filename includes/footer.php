</div>
    </div>
    <!-- Newsletter Section -->
    <section class="bg-gradient-to-r from-primary-700 to-primary-600 text-white py-14">
        <div class="container mx-auto px-4 text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-4">Restez informés</h2>
            <p class="max-w-2xl mx-auto mb-8 text-primary-100">Abonnez-vous à notre newsletter pour recevoir les dernières actualités et événements du GREFSO.</p>
            <form class="max-w-md mx-auto flex flex-col sm:flex-row shadow-lg">
                <input type="email" placeholder="Votre email" class="flex-grow px-4 py-3 rounded-l-lg focus:outline-none text-gray-800 w-full sm:w-auto">
                <button type="submit" class="bg-primary-800 hover:bg-primary-900 px-6 py-3 rounded-r-lg font-medium transition duration-300 mt-2 sm:mt-0 text-white">
                    <i class="fas fa-paper-plane mr-2"></i> S'abonner
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold mb-4"><?php echo $site_name; ?></h3>
                    <p class="text-gray-400 leading-relaxed"><?php echo $site_description; ?></p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-bold mb-4">Contact</h3>
                    <address class="text-gray-400 not-italic space-y-2">
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Faculté des Sciences Juridiques, Économiques et Sociales</p>
                        <p><i class="fas fa-phone mr-2"></i> <?php echo $site_phone; ?></p>
                        <p><i class="fas fa-envelope mr-2"></i> <?php echo $site_email; ?></p>
                    </address>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-xl font-bold mb-4">Liens rapides</h3>
                    <ul class="space-y-2 text-gray-400">
                        <?php foreach ($nav_items as $url => $item): ?>
                            <?php if ($item['title'] != 'Accueil'): ?>
                                <li><a href="<?php echo $url; ?>" class="hover:text-white transition duration-200">
                                    <i class="<?php echo $item['icon']; ?> mr-2"></i> <?php echo $item['title']; ?>
                                </a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div data-aos="fade-up" data-aos-delay="400">
                    <h3 class="text-xl font-bold mb-4">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gray-700 hover:bg-primary-600 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-gray-700 hover:bg-primary-400 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-gray-700 hover:bg-red-600 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="bg-gray-700 hover:bg-primary-800 w-10 h-10 rounded-full flex items-center justify-center transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <div class="mt-6">
                        <?php if(is_logged_in()): ?>
                            <a href="admin/dashboard.php" class="text-gray-400 hover:text-white transition duration-200">
                                <i class="fas fa-lock mr-2"></i> Administration
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="text-gray-400 hover:text-white transition duration-200">
                                <i class="fas fa-lock mr-2"></i> Espace administrateur
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $site_name; ?> - <?php echo $site_university; ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-primary-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg opacity-0 invisible transition-all duration-300 z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- AOS Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- Main JS -->
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 100,
            duration: 800
        });
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // User dropdown menu
            const userMenuButton = document.getElementById('user-menu-button');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userMenuButton && dropdownMenu) {
                userMenuButton.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.relative.group') && dropdownMenu.classList.contains('active')) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Back to top button
            const backToTopButton = document.getElementById('back-to-top');
            
            if (backToTopButton) {
                window.addEventListener('scroll', () => {
                    if (window.pageYOffset > 300) {
                        backToTopButton.classList.remove('opacity-0', 'invisible');
                        backToTopButton.classList.add('opacity-100', 'visible');
                    } else {
                        backToTopButton.classList.remove('opacity-100', 'visible');
                        backToTopButton.classList.add('opacity-0', 'invisible');
                    }
                });
                
                backToTopButton.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>

    <!-- Additional JS files -->
    <?php if (!empty($additional_js)): ?>
        <?php foreach ($additional_js as $js_file): ?>
            <script src="assets/js/main.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

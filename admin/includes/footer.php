</main>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            
            if (sidebarToggle && mobileSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('-translate-x-full');
                });
            }
            
            if (closeSidebar && mobileSidebar) {
                closeSidebar.addEventListener('click', function() {
                    mobileSidebar.classList.add('-translate-x-full');
                });
            }
            
            // Close mobile sidebar when clicking on a link
            const sidebarLinks = mobileSidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileSidebar.classList.add('-translate-x-full');
                });
            });
        });
    </script>
</body>
</html>

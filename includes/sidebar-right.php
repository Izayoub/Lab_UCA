<!-- Right Sidebar -->
<aside class="lg:w-1/4">
    <div class="bg-white p-6 rounded-xl shadow-md h-fit sticky top-24" data-aos="fade-left">
        <div class="mb-8">
            <h3 class="text-lg font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
                <i class="fas fa-bullhorn mr-2 text-primary-600"></i> Appels à Communication
            </h3>
            <div class="space-y-4">
                <?php 
                // Récupérer les appels à communication depuis la base de données
                $calls_query = "SELECT * FROM calls WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3";
                $calls_result = mysqli_query($conn, $calls_query);
                
                if ($calls_result && mysqli_num_rows($calls_result) > 0) {
                    while ($call = mysqli_fetch_assoc($calls_result)) {
                        ?>
                        <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                            <?php if ($call['is_new']): ?>
                                <span class="new-badge bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">NOUVEAU</span>
                            <?php endif; ?>
                            <h4 class="font-medium"><?php echo $call['title']; ?></h4>
                            <p class="text-sm text-gray-600 mb-2"><?php echo $call['description']; ?></p>
                            <a href="<?php echo $call['link']; ?>" class="text-primary-600 hover:underline text-sm font-medium inline-flex items-center">
                                <i class="<?php echo $call['icon']; ?> mr-1"></i> <?php echo $call['link_text']; ?>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-exclamation-circle text-2xl mb-2 text-gray-400"></i>
                        <p>Aucun appel à communication</p>
                    </div>';
                }
                ?>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
                <i class="fas fa-link mr-2 text-primary-600"></i> Liens Utiles
            </h3>
            <ul class="space-y-2">
                <?php 
                // Récupérer les liens utiles depuis la base de données
                $links_query = "SELECT * FROM useful_links WHERE is_active = 1 ORDER BY display_order";
                $links_result = mysqli_query($conn, $links_query);
                
                if ($links_result && mysqli_num_rows($links_result) > 0) {
                    while ($link = mysqli_fetch_assoc($links_result)) {
                        ?>
                        <li>
                            <a href="<?php echo $link['url']; ?>" class="flex items-center p-2 text-gray-700 hover:bg-gray-50 rounded-lg transition duration-200" target="_blank">
                                <i class="<?php echo $link['icon']; ?> mr-3 text-primary-600"></i>
                                <span><?php echo $link['title']; ?></span>
                            </a>
                        </li>
                        <?php
                    }
                } else {
                    echo '<div class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-unlink text-2xl mb-2 text-gray-400"></i>
                        <p>Aucun lien disponible</p>
                    </div>';
                }
                ?>
            </ul>
        </div>
    </div>
</aside>

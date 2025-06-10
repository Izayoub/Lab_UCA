<!-- Left Sidebar -->
<aside class="lg:w-1/4">
    <div class="bg-white p-6 rounded-xl shadow-md h-fit sticky top-24" data-aos="fade-right">
        <div class="mb-8">
            <h3 class="text-lg font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
                <i class="fas fa-bars mr-2 text-primary-600"></i> Menu
            </h3>
            <ul class="space-y-2">
                <?php 
                $current_page = get_current_page();
                foreach ($nav_items as $url => $item): 
                    $is_active = (basename($url, '.php') === $current_page);
                ?>
                    <li>
                        <a href="<?php echo $url; ?>" class="block py-2 px-3 <?php echo $is_active ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-lg transition duration-200">
                            <i class="<?php echo $item['icon']; ?> mr-2"></i> <?php echo $item['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-gray-200 flex items-center">
        <i class="fas fa-calendar-day mr-2 text-primary-600"></i> Événements à venir
    </h3>
    <div class="space-y-4">
        <?php
        $events_query = "SELECT * FROM events WHERE event_date >= CURDATE() AND is_active = 1 ORDER BY event_date LIMIT 3";
        $events_result = mysqli_query($conn, $events_query);

        if ($events_result && mysqli_num_rows($events_result) > 0) {
            while ($event = mysqli_fetch_assoc($events_result)) {
                try {
                    $event_date = new DateTime($event['event_date']);
                } catch (Exception $e) {
                    echo '<div class="text-red-500">Erreur date : ' . htmlspecialchars($event['event_date']) . '</div>';
                    continue;
                }

                // Debug temporaire
                // echo '<pre>'; print_r($event); echo '</pre>';
                ?>
                <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200 group">
                    <div class="bg-primary-600 text-white text-center rounded-lg p-2 mr-3 min-w-[60px] group-hover:bg-primary-700 transition duration-200">
                        <span class="block font-bold text-xl"><?php echo $event_date->format('d'); ?></span>
                        <span class="block text-xs"><?php echo get_month_fr_short($event_date->format('m')); ?></span>
                    </div>
                    <div>
                        <h4 class="font-medium"><?php echo htmlspecialchars($event['title']); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                <i class="far fa-calendar-times text-2xl mb-2 text-gray-400"></i>
                <p>Aucun événement à venir</p>
            </div>';
        }
        ?>
    </div>
</div>

    </div>
</aside>

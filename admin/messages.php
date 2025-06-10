<?php
// Include configuration file
require_once '../includes/config.php';

// Require login
require_login();

// Set page-specific variables
$page_title = "Messages de contact";
$active_page = "messages";

// Process form submissions
$success_message = '';
$error_message = '';

// Handle marking message as read
if (isset($_GET['mark_read'])) {
    $message_id = intval($_GET['mark_read']);
    
    $update_query = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "i", $message_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Message marqué comme lu.";
    } else {
        $error_message = "Erreur lors de la mise à jour du message: " . mysqli_error($conn);
    }
}

// Handle deleting message
if (isset($_GET['delete'])) {
    $message_id = intval($_GET['delete']);
    
    $delete_query = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $message_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Message supprimé avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression du message: " . mysqli_error($conn);
    }
}

// Get message details if ID is provided
$view_message = null;
if (isset($_GET['view'])) {
    $view_id = intval($_GET['view']);
    $view_query = "SELECT * FROM contact_messages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $view_query);
    mysqli_stmt_bind_param($stmt, "i", $view_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $view_message = mysqli_fetch_assoc($result);
        
        // Mark as read if not already
        if (!$view_message['is_read']) {
            $update_query = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "i", $view_id);
            mysqli_stmt_execute($stmt);
            
            // Update the view message is_read status
            $view_message['is_read'] = 1;
        }
    }
}

// Include header
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Messages de contact</h1>
    </div>
    
    <?php if (!empty($success_message)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><i class="fas fa-check-circle mr-2"></i> <?php echo $success_message; ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $error_message; ?></p>
        </div>
    <?php endif; ?>
    
    <?php if ($view_message): ?>
    <!-- Message Details -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Détails du message</h2>
            <a href="messages.php" class="text-blue-500 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
        </div>
        
        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold"><?php echo $view_message['subject']; ?></h3>
                <span class="text-sm text-gray-500">
                    <?php 
                    $message_date = new DateTime($view_message['created_at']);
                    echo $message_date->format('d/m/Y H:i'); 
                    ?>
                </span>
            </div>
            <div class="mt-2">
                <span class="text-sm text-gray-600">De: <strong><?php echo $view_message['name']; ?></strong> (<?php echo $view_message['email']; ?>)</span>
            </div>
        </div>
        
        <div class="mb-6">
            <p class="text-gray-700 whitespace-pre-line"><?php echo $view_message['message']; ?></p>
        </div>
        
        <div class="flex justify-between">
            <a href="mailto:<?php echo $view_message['email']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-reply mr-2"></i> Répondre
            </a>
            <a href="?delete=<?php echo $view_message['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message?')">
                <i class="fas fa-trash-alt mr-2"></i> Supprimer
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Messages List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $messages_query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
                    $messages_result = mysqli_query($conn, $messages_query);
                    
                    if ($messages_result && mysqli_num_rows($messages_result) > 0) {
                        while ($message = mysqli_fetch_assoc($messages_result)) {
                            $message_date = new DateTime($message['created_at']);
                            ?>
                            <tr class="<?php echo $message['is_read'] ? '' : 'bg-blue-50'; ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $message['name']; ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $message['email']; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-<?php echo $message['is_read'] ? 'normal' : 'semibold'; ?> text-gray-900"><?php echo $message['subject']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo $message_date->format('d/m/Y H:i'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($message['is_read']): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lu</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Non lu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?view=<?php echo $message['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <?php if (!$message['is_read']): ?>
                                    <a href="?mark_read=<?php echo $message['id']; ?>" class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-check"></i> Marquer comme lu
                                    </a>
                                    <?php endif; ?>
                                    <a href="?delete=<?php echo $message['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucun message disponible</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'includes/footer.php';
?>

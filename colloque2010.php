<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = "Colloque International | Construction de la Croissance des PME";
$additional_css = [];
$additional_js = [];

// Include header
include_once 'includes/header.php';
?>

<div class="content-area">
    <h2 class="page-title">Colloque International sur la Construction de la Croissance des PME</h2>
    
    <p>Le GREFSO et Euromed Management Marrakech ont organisé un colloque international sur le thème "Construction de la croissance des PME".</p>
    
    <p>Pour accéder au site du colloque, veuillez cliquer sur l'un des liens ci-dessous :</p>
    
    <ul class="colloque-links">
        <li><a href="pages/colloque2010/index.html" target="_blank">Page d'accueil du colloque</a></li>
        <li><a href="pages/colloque2010/presentation.html" target="_blank">Présentation</a></li>
        <li><a href="pages/colloque2010/themes.html" target="_blank">Thèmes</a></li>
        <li><a href="pages/colloque2010/comites.html" target="_blank">Comités</a></li>
        <li><a href="pages/colloque2010/communication.html" target="_blank">Appel à Communications</a></li>
        <li><a href="pages/colloque2010/calendrier.html" target="_blank">Calendrier</a></li>
        <li><a href="pages/colloque2010/inscription.html" target="_blank">Inscription</a></li>
        <li><a href="pages/colloque2010/hebergement.html" target="_blank">Hébergement</a></li>
        <li><a href="pages/colloque2010/programme.html" target="_blank">Programme</a></li>
        <li><a href="pages/colloque2010/atelierdoctorant.html" target="_blank">Atelier Doctoral</a></li>
        <li><a href="pages/colloque2010/contact.html" target="_blank">Contact</a></li>
    </ul>
    
    <div class="iframe-container">
        <iframe src="pages/colloque2010/index.html" width="100%" height="600" frameborder="0"></iframe>
    </div>
</div>

<style>
    .colloque-links {
        margin-bottom: 20px;
    }
    
    .colloque-links li {
        margin-bottom: 5px;
    }
    
    .iframe-container {
        position: relative;
        overflow: hidden;
        padding-top: 56.25%;
        margin-top: 30px;
    }
    
    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 600px;
        border: 1px solid #ddd;
    }
</style>

<?php
// Include footer
include_once 'includes/footer.php';
?>
<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = "Journées Doctoriales en Management";
$additional_css = [];
$additional_js = [];

// Include header
include_once 'includes/header.php';
?>

<div class="content-area">
    <h2 class="page-title">Journées Doctoriales en Management</h2>
    
    <p>Les groupes de recherche en gestion de l'Université Cadi Ayyad (GREFSO, GREMID, GREGO, LQUALIMAT et NPG) organisent régulièrement les Journées Doctoriales en Management à Marrakech.</p>
    
    <p>Pour accéder au site des Journées Doctoriales, veuillez cliquer sur l'un des liens ci-dessous :</p>
    
    <ul class="jdm-links">
        <li><a href="pages/JDM/index.html" target="_blank">Page d'accueil des Journées Doctoriales</a></li>
        <li><a href="pages/JDM/PJDM.html" target="_blank">Présentation</a></li>
        <li><a href="pages/JDM/appel.html" target="_blank">Appel à présentation</a></li>
        <li><a href="pages/JDM/comites.html" target="_blank">Comités</a></li>
        <li><a href="pages/JDM/info.html" target="_blank">Infos Pratiques</a></li>
        <li><a href="pages/JDM/programme.html" target="_blank">Programme</a></li>
        <li><a href="pages/JDM/hebergement.html" target="_blank">Hébergement</a></li>
        <li><a href="pages/JDM/contact.html" target="_blank">Contact</a></li>
    </ul>
    
    <div class="iframe-container">
        <iframe src="pages/JDM/index.html" width="100%" height="600" frameborder="0"></iframe>
    </div>
</div>

<style>
    .jdm-links {
        margin-bottom: 20px;
    }
    
    .jdm-links li {
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
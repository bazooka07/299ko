{# Template d'aide pour le plugin ConfigManager - Système intégré du CMS #}

<div class="help-content">
    <h3>Aide - Configuration du site</h3>
    
    <h4>Paramètres généraux</h4>
    <ul>
        <li><strong>Nom du site :</strong> Le nom qui apparaît dans le titre des pages et dans la navigation.</li>
        <li><strong>Description du site :</strong> Description utilisée pour le référencement (meta description).</li>
        <li><strong>Langue du site :</strong> Langue principale du site.</li>
        <li><strong>Thème du site :</strong> Thème visuel utilisé pour l'affichage public.</li>
        <li><strong>Plugin par défaut (public) :</strong> Plugin affiché sur la page d'accueil du site.</li>
        <li><strong>Plugin par défaut (admin) :</strong> Plugin affiché par défaut dans l'administration.</li>
        <li><strong>Masquer le titre des pages :</strong> Cache les titres H1 sur les pages publiques.</li>
    </ul>
    
    <h4>Paramètres avancés</h4>
    <ul>
        <li><strong>URL du site :</strong> URL complète du site sans slash final (ex: https://monsite.com).</li>
        <li><strong>Mode débogage :</strong> Affiche les erreurs PHP et les informations de débogage.</li>
        <li><strong>Fichier .htaccess :</strong> Contenu du fichier .htaccess pour la configuration Apache.</li>
    </ul>
    
    <h4>Système de cache</h4>
    <p><strong>Vue d'ensemble :</strong></p>
    <ul>
        <li><strong>Activation :</strong> Active ou désactive complètement le système de cache. Désactivé automatiquement en mode administration.</li>
        <li><strong>Durée :</strong> Temps de vie des fichiers de cache (3600 = 1 heure). Les pages sont régénérées après expiration.</li>
        <li><strong>Minification :</strong> Réduit la taille du HTML en supprimant espaces et commentaires tout en préservant les scripts et styles.</li>
        <li><strong>Lazy loading :</strong> Ajoute automatiquement loading="lazy" aux images/iframes et alt="" aux images sans attribut alt.</li>
    </ul>
    <p><strong>Avantages de performance :</strong></p>
    <ul>
        <li>Pages chargées instantanément depuis le cache</li>
        <li>Réduction de la charge serveur et de la bande passante</li>
        <li>Amélioration du référencement et de l'expérience utilisateur</li>
    </ul>
    
    <h4>Sauvegarde et restauration</h4>
    <p>Le système de sauvegarde permet de créer des sauvegardes complètes du site incluant :</p>
    <ul>
        <li>Fichiers du site</li>
        <li>Base de données</li>
        <li>Configuration</li>
        <li>Plugins et thèmes</li>
    </ul>
    <p>Les sauvegardes sont stockées dans le dossier <code>data/backups/</code> et peuvent être téléchargées ou restaurées.</p>
    
    <h4>Mises à jour</h4>
    <p>Le système vérifie automatiquement les mises à jour disponibles et vous notifie quand une nouvelle version est disponible. N'oubliez pas de faire une sauvegarde avant toute mise à jour.</p>
</div> 
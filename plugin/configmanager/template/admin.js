/**
 * Admin JavaScript for ConfigManager plugin
 * Handles help popup functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    const helpIcons = document.querySelectorAll('.help-icon');
    const helpPopup = document.getElementById('help-popup');
    const helpContent = document.getElementById('help-content');
    
    // Help content for different sections
    const helpContentData = {
        'cache': {
            title: 'Aide - Système de cache',
            content: `
                <h4>Aide - Système de cache</h4>
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
            `
        }
    };
    
    helpIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const helpType = this.getAttribute('data-help');
            const content = helpContentData[helpType];
            
            if (content) {
                helpContent.innerHTML = content.content;
                showHelpPopup(this);
            }
        });
    });
    
    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (!helpPopup.contains(e.target) && !e.target.classList.contains('help-icon')) {
            closeHelpPopup();
        }
    });
    
    // Close popup on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeHelpPopup();
        }
    });
});

/**
 * Show help popup at the center of the page
 * @param {HTMLElement} icon - The help icon element
 */
function showHelpPopup(icon) {
    const popup = document.getElementById('help-popup');
    
    // Center the popup in the middle of the page
    popup.style.left = '50%';
    popup.style.top = '50%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.display = 'block';
}

/**
 * Close the help popup
 */
function closeHelpPopup() {
    document.getElementById('help-popup').style.display = 'none';
} 
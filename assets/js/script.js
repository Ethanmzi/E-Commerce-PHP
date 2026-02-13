/**
 * script.js - Gestion des interactions Front-End
 */

document.addEventListener('DOMContentLoaded', function() {

    // 1. GESTION DU PANIER : Confirmation de suppression
    // Sélectionne tous les boutons de suppression (poubelles) dans le panier
    const deleteButtons = document.querySelectorAll('.btn-outline-danger');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Affiche une boîte de dialogue de confirmation
            const confirmation = confirm('Voulez-vous vraiment retirer cet article du panier ?');
            if (!confirmation) {
                e.preventDefault(); // Annule l'action si l'utilisateur clique sur "Annuler"
            }
        });
    });


    // 2. VALIDATION DES FORMULAIRES (Bootstrap 5)
    // Empêche l'envoi des formulaires s'il y a des champs invalides
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });


    // 3. ANIMATION AU SCROLL : Navbar
    // Ajoute une ombre à la navbar quand on commence à défiler
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            nav.classList.add('shadow-lg');
        } else {
            nav.classList.remove('shadow-lg');
        }
    });


    // 4. APERÇU DE L'IMAGE (Pour le Back-Office)
    // Utile pour voir l'image avant de l'envoyer sur le serveur
    const imageInput = document.querySelector('#productImageInput');
    const imagePreview = document.querySelector('#productImagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none'); // Affiche l'image
                }
                reader.readAsDataURL(file);
            }
        });
    }

});

// 5. FONCTION : Mise à jour dynamique du prix (Fiche produit)
// Optionnel : Si tu veux changer le prix total quand on change la quantité
function updateTotalPrice(unitPrice) {
    const qtyInput = document.querySelector('#quantityInput');
    const totalDisplay = document.querySelector('#totalPriceDisplay');
    
    if (qtyInput && totalDisplay) {
        qtyInput.addEventListener('input', function() {
            let total = (unitPrice * this.value).toFixed(2);
            totalDisplay.innerText = total + ' €';
        });
    }
}

// 6. GALERIE D'IMAGES PRODUIT : Gestion des miniatures
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('#carouselProduit');
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    
    if (carousel && thumbnails.length > 0) {
        // Gestion du clic sur les miniatures
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', function() {
                // Retirer la classe active de toutes les miniatures
                thumbnails.forEach(t => t.classList.remove('active'));
                // Ajouter la classe active à la miniature cliquée
                this.classList.add('active');
            });
        });

        // Synchroniser les miniatures avec le carrousel Bootstrap
        carousel.addEventListener('slid.bs.carousel', function(event) {
            const activeIndex = event.to;
            thumbnails.forEach((thumb, index) => {
                if (index === activeIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        });

        // Support du swipe tactile pour mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carousel);
            
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe vers la gauche -> image suivante
                bsCarousel.next();
            } else if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe vers la droite -> image précédente
                bsCarousel.prev();
            }
        }
    }
});
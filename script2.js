document.addEventListener("DOMContentLoaded", function () {
    // Wishlist Popup
    const wishlistIcon = document.getElementById("wishlist-icon");
    const wishlistPopup = document.getElementById("wishlist-popup");
    const wishlistClose = document.getElementById("wishlist-close");
    
    wishlistIcon.addEventListener("click", function () {
        wishlistPopup.style.display = "block";
    });
    
    wishlistClose.addEventListener("click", function () {
        wishlistPopup.style.display = "none";
    });
    
    // Cart Popup
    const cartIcon = document.getElementById("cart-icon");
    const cartPopup = document.getElementById("cart-popup");
    const cartClose = document.getElementById("cart-close");
    
    cartIcon.addEventListener("click", function () {
        cartPopup.style.display = "block";
    });
    
    cartClose.addEventListener("click", function () {
        cartPopup.style.display = "none";
    });
    
    // Close popup when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === wishlistPopup) {
            wishlistPopup.style.display = "none";
        }
        if (event.target === cartPopup) {
            cartPopup.style.display = "none";
        }
    });
});
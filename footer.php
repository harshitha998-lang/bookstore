<html>
<head>
<style>
/* Footer Styling */
footer {
    background: #232f3e;
    color: white;
    padding: 40px 0;
    text-align: center;
}

.footer-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: auto;
    text-align: left;
}

.footer-section {
    width: 22%;
    padding: 10px;
}

.footer-section h3 {
    border-bottom: 2px solid #ff9800;
    display: inline-block;
    padding-bottom: 5px;
    margin-bottom: 10px;
    font-size: 18px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 8px 0;
}

.footer-section ul li a {
    color: white;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.footer-section ul li a:hover {
    color: #ff9800;
    text-decoration: underline;
}

.footer-bottom {
    margin-top: 20px;
    font-size: 14px;
    text-align: center;
}

/* Responsive Footer */
@media screen and (max-width: 768px) {
    .footer-container {
        flex-direction: column;
        text-align: center;
    }
    .footer-section {
        width: 100%;
        padding: 10px 0;
    }
}
</style>
</head>
<body>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p>Sector 55, Gurgaon - 122003</p>
            <p>Call us: <a href="tel:+918089866088">+91 8089866088</a></p>
            <p>Email: <a href="mailto:support@bookstore.in">support@bookstore.in</a></p>
            <p>WhatsApp: <a href="https://wa.me/9592218569" target="_blank">8089866088</a></p>
        </div>

        <div class="footer-section">
            <h3>Find It Fast</h3>
            <ul>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="books.php">All Books</a></li>
                <li><a href="#">Track Your Order</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="my_orders.php">My orders</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Customer Care</h3>
            <ul>
                <li><a href="myaccount.php">My Account</a></li>
                <li><a href="#">Contact Us on Instagram</a></li>
                <li><a href="#">Email Us</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
                <li><a href="aboutus.html">About Us</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>Policies</h3>
            <ul>
                <li><a href="returnpolicy.php">Returns & Refunds Policy</a></li>
                <li><a href="shippingpolicy.php">Shipping Policy</a></li>
                <li><a href="privacypolicy.php">Privacy Policy</a></li>
                <li><a href="termscondi.php">Terms & Conditions</a></li>
                <li><a href="cancelation.php">Cancellation Policy</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">© 2025 Bookstore. All Rights Reserved.</div>
</footer>
</body>
</html>
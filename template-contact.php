<?php
/**
 * Template Name: Contact Us
 *
 * Template for displaying the Contact Us page
 *
 * @package PriyanshTours
 */

get_header();
?>

<div class="contact-us-page-wrapper">
    <!-- Hero Section -->
    <section class="contact-hero-section">
        <div class="container">
            <h1 class="page-title">Contact Us</h1>
            <div class="subtitle">We'd love to hear from you. Get in touch with our team.</div>
        </div>
    </section>

    <div class="container">
        <div class="contact-content">
            <!-- Contact Form and Info Container -->
            <div class="contact-main-container">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <div class="contact-form-header">
                        <h2>Send Us a Message</h2>
                        <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>
                    
                    <div class="contact-form">
                        <?php
                        // Check if Contact Form 7 is active
                        if (shortcode_exists('contact-form-7')) {
                            // Replace the ID with your Contact Form 7 form ID
                            echo do_shortcode('[contact-form-7 id="123" title="Contact Form"]');
                        } else {
                            // Fallback HTML form
                            ?>
                            <form id="contact-form" action="#" method="post">
                                <div class="form-group">
                                    <label for="name">Name*</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" id="phone" name="phone">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Subject*</label>
                                    <input type="text" id="subject" name="subject" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Message*</label>
                                    <textarea id="message" name="message" rows="5" required></textarea>
                                </div>
                                
                                <div class="form-submit">
                                    <button type="submit" class="submit-button">Send Message</button>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="contact-info-container">
                    <div class="contact-info">
                        <h3>Contact Information</h3>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bx bx-map"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Address</span>
                                <span class="info-value">Karawal Nagar, Delhi, India</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bx bx-phone"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Phone</span>
                                <span class="info-value">+91 987 654 3210</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bx bx-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Email</span>
                                <span class="info-value">contact@priyanshtours.com</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bx bx-time"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Working Hours</span>
                                <span class="info-value">Monday-Saturday: 9AM - 6PM</span>
                            </div>
                        </div>
                        
                        <!-- Social Media Links -->
                        <div class="social-links">
                            <h4>Follow Us</h4>
                            <div class="social-icons">
                                <a href="#" class="social-icon" title="Facebook"><i class="bx bxl-facebook"></i></a>
                                <a href="#" class="social-icon" title="Instagram"><i class="bx bxl-instagram"></i></a>
                                <a href="#" class="social-icon" title="Twitter"><i class="bx bxl-twitter"></i></a>
                                <a href="#" class="social-icon" title="LinkedIn"><i class="bx bxl-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="contact-map-container">
                <h3>Find Us</h3>
                <div class="contact-map">
                    <!-- Google Maps embed for Delhi Karawal Nagar -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55977.39872606539!2d77.24821905820311!3d28.70847230000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfbfec5958f69%3A0x1bbc9ef52ef0b4c3!2sKarawal%20Nagar%2C%20Delhi!5e0!3m2!1sen!2sin!4v1690557951622!5m2!1sen!2sin" 
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <section class="contact-faq-section">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>How do I book a tour?</h4>
                        <span class="faq-toggle"><i class="bx bx-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>You can book a tour by visiting our Tour Packages page, selecting your desired tour, and following the booking process. If you need assistance, feel free to call or email us directly.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>What payment methods do you accept?</h4>
                        <span class="faq-toggle"><i class="bx bx-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>We accept credit/debit cards, PayPal, bank transfers, and UPI payments for Indian customers. All payments are secure and encrypted.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>Can you arrange custom tours?</h4>
                        <span class="faq-toggle"><i class="bx bx-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! We specialize in creating custom tour itineraries tailored to your preferences. Contact us with your requirements and our team will design the perfect trip for you.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>What is your cancellation policy?</h4>
                        <span class="faq-toggle"><i class="bx bx-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Our cancellation policy varies depending on the tour package. Generally, cancellations made 30 days or more before departure receive a full refund minus a small administrative fee. Please check the specific tour details for more information.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for FAQ toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const icon = this.querySelector('.faq-toggle i');
            
            // Toggle the active class
            faqItem.classList.toggle('active');
            
            // Change the icon
            if (faqItem.classList.contains('active')) {
                icon.classList.replace('bx-plus', 'bx-minus');
            } else {
                icon.classList.replace('bx-minus', 'bx-plus');
            }
        });
    });
    
    // Form validation
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Simple form validation example
            const nameField = document.getElementById('name');
            const emailField = document.getElementById('email');
            const messageField = document.getElementById('message');
            
            let isValid = true;
            
            if (!nameField.value.trim()) {
                isValid = false;
                nameField.classList.add('error');
            } else {
                nameField.classList.remove('error');
            }
            
            if (!emailField.value.trim() || !emailField.value.includes('@')) {
                isValid = false;
                emailField.classList.add('error');
            } else {
                emailField.classList.remove('error');
            }
            
            if (!messageField.value.trim()) {
                isValid = false;
                messageField.classList.add('error');
            } else {
                messageField.classList.remove('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });
    }
});
</script>

<?php get_footer(); ?> 
<?php
/**
 * Custom Lost Password Form template for Priyansh Tours.
 * Styled with Shadcn UI inspired components.
 *
 * @package Priyansh_Tours_Theme
 */

// Print Errors / Notices.
wptravel_print_notices();

// Enqueue the auth styles
wp_enqueue_style('priyanshtours-auth-styles', get_template_directory_uri() . '/assets/css/auth-pages.css', array(), '1.0.0');
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php wp_head(); ?>
</head>
<body class="wp-travel-auth-page">
    <div class="auth-container">
        <div class="auth-decoration"></div>
        <div class="auth-decoration"></div>
        
        <div class="auth-card">
            <div class="auth-card-header">
                <div class="auth-logo">
                    <?php
                    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>">
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <h1 class="auth-card-title"><?php esc_html_e('Reset Password', 'wp-travel'); ?></h1>
                <p class="auth-card-description"><?php esc_html_e('Enter your email to receive a password reset link', 'wp-travel'); ?></p>
            </div>

            <form method="post" class="auth-form active">
                <div class="form-group">
                    <label class="form-label" for="user_login"><?php esc_html_e('Email or Username', 'wp-travel'); ?></label>
                    <input id="user_login" class="form-input" type="text" name="user_login" placeholder="<?php esc_attr_e('Enter your email address', 'wp-travel'); ?>" required />
                </div>

                <div class="auth-info-box" style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background-color: rgba(245, 122, 69, 0.1); border-radius: var(--radius); border-left: 4px solid var(--primary);">
                    <p style="margin: 0; font-size: 0.875rem; color: var(--primary);">
                        <?php esc_html_e('Password reset instructions will be sent to your registered email address.', 'wp-travel'); ?>
                    </p>
                </div>
                
                <?php wp_nonce_field('wp-travel-lost-password', 'wp-travel-lost-password-nonce'); ?>
                
                <button type="submit" name="wp-travel-reset-password" value="Reset Password" class="auth-button">
                    <i class='bx bx-envelope'></i>
                    <?php esc_html_e('Send Reset Link', 'wp-travel'); ?>
                </button>
                
                <div class="auth-links" style="justify-content: center; margin-top: 1.5rem;">
                    <a href="<?php echo esc_url(wptravel_get_page_permalink('wp-travel-dashboard')); ?>" class="auth-link">
                        <i class='bx bx-arrow-back'></i>
                        <?php esc_html_e('Back to Login', 'wp-travel'); ?>
                    </a>
                </div>
            </form>
            
            <!-- Security Tips Section -->
            <div class="social-proof">
                <h4 class="social-proof-heading"><?php esc_html_e('Security Tips', 'wp-travel'); ?></h4>
                <ul style="text-align: left; font-size: 0.875rem; color: var(--muted-foreground); padding-left: 1.5rem; margin: 0;">
                    <li style="margin-bottom: 0.5rem;"><?php esc_html_e('Create a strong password with letters, numbers, and symbols', 'wp-travel'); ?></li>
                    <li style="margin-bottom: 0.5rem;"><?php esc_html_e('Never share your login credentials with others', 'wp-travel'); ?></li>
                    <li><?php esc_html_e('Always log out when using shared computers', 'wp-travel'); ?></li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.querySelector('.auth-form');
        form.addEventListener('submit', function(e) {
            const userLogin = document.getElementById('user_login');
            
            if (!userLogin.value.trim()) {
                e.preventDefault();
                userLogin.style.borderColor = 'var(--destructive)';
                
                let errorMsg = userLogin.parentNode.querySelector('.input-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'input-error';
                    errorMsg.style.color = 'var(--destructive)';
                    errorMsg.style.fontSize = '0.75rem';
                    errorMsg.style.marginTop = '0.25rem';
                    userLogin.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Please enter your email or username';
            } else {
                userLogin.style.borderColor = '';
                const errorMsg = userLogin.parentNode.querySelector('.input-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        // Real-time validation
        const userLogin = document.getElementById('user_login');
        userLogin.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = 'var(--destructive)';
                
                let errorMsg = this.parentNode.querySelector('.input-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'input-error';
                    errorMsg.style.color = 'var(--destructive)';
                    errorMsg.style.fontSize = '0.75rem';
                    errorMsg.style.marginTop = '0.25rem';
                    this.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Please enter your email or username';
            } else {
                this.style.borderColor = '';
                const errorMsg = this.parentNode.querySelector('.input-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html> 
<?php
/**
 * Custom Login / Register Form template for Priyansh Tours.
 * Styled with Shadcn UI inspired components.
 *
 * @package Priyansh_Tours_Theme
 */

// Print Errors / Notices.
wptravel_print_notices();

$nonce_value = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
$nonce_value = isset( $_POST['wp-travel-register-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp-travel-register-nonce'] ) ) : $nonce_value;

$login_form_class = 'active';
$reg_form_class   = '';

$settings = wptravel_get_settings();

$enable_my_account_customer_registration = isset( $settings['enable_my_account_customer_registration'] ) ? $settings['enable_my_account_customer_registration'] : 'yes';

$generate_username_from_email = isset( $settings['generate_username_from_email'] ) ? $settings['generate_username_from_email'] : 'no';
$generate_user_password       = isset( $settings['generate_user_password'] ) ? $settings['generate_user_password'] : 'no';

if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-register' ) ) {
    $login_form_class = '';
    $reg_form_class   = 'active';
}

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
                <h1 class="auth-card-title"><?php echo $login_form_class ? esc_html__('Welcome Back', 'wp-travel') : esc_html__('Create Account', 'wp-travel'); ?></h1>
                <p class="auth-card-description"><?php echo $login_form_class ? esc_html__('Sign in to access your account', 'wp-travel') : esc_html__('Join us for better travel experiences', 'wp-travel'); ?></p>
            </div>

            <?php
            // Print Errors / Notices.
            wptravel_print_notices();
            
            $nonce_value = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
            $nonce_value = isset( $_POST['wp-travel-register-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp-travel-register-nonce'] ) ) : $nonce_value;
            
            $login_form_class = 'active';
            $reg_form_class   = '';
            
            $settings = wptravel_get_settings();
            
            $enable_my_account_customer_registration = isset( $settings['enable_my_account_customer_registration'] ) ? $settings['enable_my_account_customer_registration'] : 'yes';
            
            $generate_username_from_email = isset( $settings['generate_username_from_email'] ) ? $settings['generate_username_from_email'] : 'no';
            $generate_user_password       = isset( $settings['generate_user_password'] ) ? $settings['generate_user_password'] : 'no';
            
            if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'wp-travel-register' ) ) {
                $login_form_class = '';
                $reg_form_class   = 'active';
            }
            

            ?>

            <?php if ( 'yes' === $enable_my_account_customer_registration ) : ?>
                <!-- Registration form -->
                <form method="post" class="auth-form register-form <?php echo esc_attr($reg_form_class); ?>">
                    <?php if ( 'no' === $generate_username_from_email ) : ?>
                        <div class="form-group">
                            <label class="form-label" for="reg_username"><?php esc_html_e('Username', 'wp-travel'); ?> <span class="required">*</span></label>
                            <input id="reg_username" class="form-input" name="username" type="text" placeholder="<?php esc_attr_e('Choose a username', 'wp-travel'); ?>" required />
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label" for="reg_email"><?php esc_html_e('Email Address', 'wp-travel'); ?> <span class="required">*</span></label>
                        <input id="reg_email" class="form-input" name="email" type="email" placeholder="<?php esc_attr_e('Your email address', 'wp-travel'); ?>" required />
                    </div>
                    
                    <?php if ( 'no' === $generate_user_password ) : ?>
                        <div class="form-group">
                            <label class="form-label" for="reg_password"><?php esc_html_e('Password', 'wp-travel'); ?> <span class="required">*</span></label>
                            <input id="reg_password" class="form-input" name="password" type="password" placeholder="<?php esc_attr_e('Create a password', 'wp-travel'); ?>" required />
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="reg_confirm_password"><?php esc_html_e('Confirm Password', 'wp-travel'); ?> <span class="required">*</span></label>
                            <input id="reg_confirm_password" class="form-input" name="confirm_password" type="password" placeholder="<?php esc_attr_e('Confirm your password', 'wp-travel'); ?>" required />
                        </div>
                    <?php endif; ?>
                    
                    <?php do_action('wp_travel_after_registration_form_password', $settings); ?>

                    <?php wp_nonce_field('wp-travel-register', 'wp-travel-register-nonce'); ?>
                    
                    <button type="submit" name="register" value="<?php esc_attr_e('Register', 'wp-travel'); ?>" class="auth-button">
                        <i class='bx bx-user-plus'></i>
                        <?php esc_html_e('Create Account', 'wp-travel'); ?>
                    </button>
                    
                    <div class="social-login-separator">
                        <span><?php esc_html_e('Or sign up with', 'wp-travel'); ?></span>
                    </div>
                    
                    <div class="social-login-buttons">
                        <button type="button" class="social-login-button google-login">
                            <i class='bx bxl-google'></i>
                            <span><?php esc_html_e('Google', 'wp-travel'); ?></span>
                        </button>
                        
                        <button type="button" class="social-login-button facebook-login">
                            <i class='bx bxl-facebook-square'></i>
                            <span><?php esc_html_e('Facebook', 'wp-travel'); ?></span>
                        </button>
                        
                        <button type="button" class="social-login-button apple-login">
                            <i class='bx bxl-apple'></i>
                            <span><?php esc_html_e('Apple', 'wp-travel'); ?></span>
                        </button>
                    </div>
                    
                    <div class="auth-links">
                        <span><?php esc_html_e('Already have an account?', 'wp-travel'); ?></span>
                        <a href="#" class="auth-link toggle-form" data-target="login">
                            <?php esc_html_e('Sign In', 'wp-travel'); ?>
                        </a>
                    </div>
                </form>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="post" class="auth-form login-form <?php echo esc_attr($login_form_class); ?>">
                <div class="form-group">
                    <label class="form-label" for="username"><?php esc_html_e('Username or Email', 'wp-travel'); ?></label>
                    <input id="username" class="form-input" name="username" type="text" placeholder="<?php esc_attr_e('Enter your username', 'wp-travel'); ?>" required />
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password"><?php esc_html_e('Password', 'wp-travel'); ?></label>
                    <input id="password" class="form-input" name="password" type="password" placeholder="<?php esc_attr_e('Enter your password', 'wp-travel'); ?>" required />
                </div>
                
                <div class="auth-links">
                    <div class="form-checkbox-group">
                        <input class="form-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                        <label for="rememberme" class="form-checkbox-label">
                            <?php esc_html_e('Remember me', 'wp-travel'); ?>
                        </label>
                    </div>
                    
                    <a href="<?php echo esc_url(wptravel_lostpassword_url()); ?>" class="auth-link">
                        <?php esc_html_e('Forgot Password?', 'wp-travel'); ?>
                    </a>
                </div>
                
                <?php wp_nonce_field('wp-travel-login', 'wp-travel-login-nonce'); ?>
                
                <button type="submit" name="login" value="<?php esc_attr_e('Login', 'wp-travel'); ?>" class="auth-button">
                    <i class='bx bx-log-in'></i>
                    <?php esc_html_e('Sign In', 'wp-travel'); ?>
                </button>
                
                <div class="social-login-separator">
                    <span><?php esc_html_e('Or continue with', 'wp-travel'); ?></span>
                </div>
                
                <div class="social-login-buttons">
                    <button type="button" class="social-login-button google-login">
                        <i class='bx bxl-google'></i>
                        <span><?php esc_html_e('Google', 'wp-travel'); ?></span>
                    </button>
                    
                    <button type="button" class="social-login-button facebook-login">
                        <i class='bx bxl-facebook-square'></i>
                        <span><?php esc_html_e('Facebook', 'wp-travel'); ?></span>
                    </button>
                    
                    <button type="button" class="social-login-button apple-login">
                        <i class='bx bxl-apple'></i>
                        <span><?php esc_html_e('Apple', 'wp-travel'); ?></span>
                    </button>
                </div>
                
                <?php if ('yes' === $enable_my_account_customer_registration) : ?>
                    <div class="auth-links">
                        <span><?php esc_html_e("Don't have an account?", 'wp-travel'); ?></span>
                        <a href="#" class="auth-link toggle-form" data-target="register">
                            <?php esc_html_e('Create Account', 'wp-travel'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </form>
            

        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form toggle functionality
        const toggleLinks = document.querySelectorAll('.toggle-form');
        
        toggleLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                
                const loginForm = document.querySelector('.login-form');
                const registerForm = document.querySelector('.register-form');
                
                if (target === 'login') {
                    loginForm.classList.add('active');
                    registerForm.classList.remove('active');
                    document.querySelector('.auth-card-title').textContent = '<?php echo esc_js(__('Welcome Back', 'wp-travel')); ?>';
                    document.querySelector('.auth-card-description').textContent = '<?php echo esc_js(__('Sign in to access your account', 'wp-travel')); ?>';
                } else {
                    registerForm.classList.add('active');
                    loginForm.classList.remove('active');
                    document.querySelector('.auth-card-title').textContent = '<?php echo esc_js(__('Create Account', 'wp-travel')); ?>';
                    document.querySelector('.auth-card-description').textContent = '<?php echo esc_js(__('Join us for better travel experiences', 'wp-travel')); ?>';
                }
            });
        });
        
        // Make sure forms are properly displayed initially
        const loginForm = document.querySelector('.login-form');
        const registerForm = document.querySelector('.register-form');

        // Check if form should be active based on PHP variables (for form submission errors)
        if (<?php echo !empty($_POST['register']) && wp_verify_nonce($nonce_value, 'wp-travel-register') ? 'true' : 'false'; ?>) {
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
            document.querySelector('.auth-card-title').textContent = '<?php echo esc_js(__('Create Account', 'wp-travel')); ?>';
            document.querySelector('.auth-card-description').textContent = '<?php echo esc_js(__('Join us for better travel experiences', 'wp-travel')); ?>';
        }
        
        // Form validation
        const forms = document.querySelectorAll('.auth-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const inputs = this.querySelectorAll('input[required]');
                let isValid = true;
                
                // Clear all previous error messages
                const errorMsgs = this.querySelectorAll('.input-error');
                errorMsgs.forEach(errorMsg => errorMsg.remove());
                
                // Check required fields
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = 'var(--destructive)';
                        
                        // Create error message if it doesn't exist
                        let errorMsg = input.parentNode.querySelector('.input-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('span');
                            errorMsg.className = 'input-error';
                            errorMsg.style.color = 'var(--destructive)';
                            errorMsg.style.fontSize = '0.75rem';
                            errorMsg.style.marginTop = '0.25rem';
                            input.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'This field is required';
                    } else {
                        input.style.borderColor = '';
                    }
                    
                    // Email validation
                    if (input.type === 'email' && !/\S+@\S+\.\S+/.test(input.value)) {
                        isValid = false;
                        input.style.borderColor = 'var(--destructive)';
                        
                        let errorMsg = input.parentNode.querySelector('.input-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('span');
                            errorMsg.className = 'input-error';
                            errorMsg.style.color = 'var(--destructive)';
                            errorMsg.style.fontSize = '0.75rem';
                            errorMsg.style.marginTop = '0.25rem';
                            input.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'Please enter a valid email address';
                    }
                });
                
                // Check password confirmation match if registration form
                if (this.classList.contains('register-form')) {
                    const password = this.querySelector('#reg_password');
                    const confirmPassword = this.querySelector('#reg_confirm_password');
                    
                    if (password && confirmPassword && password.value !== confirmPassword.value) {
                        isValid = false;
                        confirmPassword.style.borderColor = 'var(--destructive)';
                        
                        let errorMsg = confirmPassword.parentNode.querySelector('.input-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('span');
                            errorMsg.className = 'input-error';
                            errorMsg.style.color = 'var(--destructive)';
                            errorMsg.style.fontSize = '0.75rem';
                            errorMsg.style.marginTop = '0.25rem';
                            confirmPassword.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'Passwords do not match';
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Real-time validation
            const inputs = form.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
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
                        errorMsg.textContent = 'This field is required';
                    } else {
                        this.style.borderColor = '';
                        const errorMsg = this.parentNode.querySelector('.input-error');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                        
                        // Email validation
                        if (this.type === 'email' && !/\S+@\S+\.\S+/.test(this.value)) {
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
                            errorMsg.textContent = 'Please enter a valid email address';
                        }
                    }
                });
            });
            
            // Real-time password confirmation validation
            if (form.classList.contains('register-form')) {
                const password = form.querySelector('#reg_password');
                const confirmPassword = form.querySelector('#reg_confirm_password');
                
                if (password && confirmPassword) {
                    confirmPassword.addEventListener('input', function() {
                        if (this.value && password.value !== this.value) {
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
                            errorMsg.textContent = 'Passwords do not match';
                        } else if (this.value) {
                            this.style.borderColor = 'var(--ring)';
                            
                            const errorMsg = this.parentNode.querySelector('.input-error');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }
                    });
                    
                    password.addEventListener('input', function() {
                        if (confirmPassword.value && this.value !== confirmPassword.value) {
                            confirmPassword.style.borderColor = 'var(--destructive)';
                            
                            let errorMsg = confirmPassword.parentNode.querySelector('.input-error');
                            if (!errorMsg) {
                                errorMsg = document.createElement('span');
                                errorMsg.className = 'input-error';
                                errorMsg.style.color = 'var(--destructive)';
                                errorMsg.style.fontSize = '0.75rem';
                                errorMsg.style.marginTop = '0.25rem';
                                confirmPassword.parentNode.appendChild(errorMsg);
                            }
                            errorMsg.textContent = 'Passwords do not match';
                        } else if (confirmPassword.value) {
                            confirmPassword.style.borderColor = 'var(--ring)';
                            
                            const errorMsg = confirmPassword.parentNode.querySelector('.input-error');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }
                    });
                }
            }
        });
        
        // Handle social login buttons
        const socialLoginButtons = document.querySelectorAll('.social-login-button');
        socialLoginButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the provider from the button class
                let provider = '';
                if (this.classList.contains('google-login')) {
                    provider = 'Google';
                } else if (this.classList.contains('facebook-login')) {
                    provider = 'Facebook';
                } else if (this.classList.contains('apple-login')) {
                    provider = 'Apple';
                }
                
                // Create a notification element
                const notification = document.createElement('div');
                notification.className = 'auth-success';
                notification.innerHTML = `<p>${provider} login clicked. This is a placeholder for ${provider} OAuth integration.</p>`;
                
                // Find the right place to show the notification
                const form = this.closest('.auth-form');
                const cardHeader = document.querySelector('.auth-card-header');
                
                // Insert notification after the header
                cardHeader.insertAdjacentElement('afterend', notification);
                
                // Remove the notification after 3 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s ease';
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
                
                // Log to console (for development purposes)
                console.log(`${provider} login clicked. Integration with ${provider} OAuth would happen here.`);
            });
        });
    });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html> 
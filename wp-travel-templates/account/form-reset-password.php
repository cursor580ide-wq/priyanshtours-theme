<?php
/**
 * Custom Reset Password Form template for Priyansh Tours.
 * Styled with Shadcn UI inspired components.
 *
 * @package Priyansh_Tours_Theme
 */

// Print Errors / Notices.
wptravel_print_notices();

// Get key and login from query string.
$reset_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$user_id   = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
$reset_login = isset( $args['login'] ) ? $args['login'] : '';
$user      = false;

// If no password reset key or user id, exit with error.
if ( empty( $reset_key ) || empty( $user_id ) ) {
    wptravel_add_notice( __( 'Invalid password reset link. Please request a new link below.', 'wp-travel' ), 'error' );
    wptravel_print_notices();
    return;
}

// Verify the reset key for the user.
$user = check_password_reset_key( $reset_key, $reset_login );
if ( is_wp_error( $user ) ) {
    wptravel_add_notice( __( 'This password reset link has expired or is invalid. Please request a new link below.', 'wp-travel' ), 'error' );
    wptravel_print_notices();
    return;
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
                <h1 class="auth-card-title"><?php esc_html_e('Create New Password', 'wp-travel'); ?></h1>
                <p class="auth-card-description"><?php esc_html_e('Enter and confirm your new password below', 'wp-travel'); ?></p>
            </div>

            <form method="post" class="auth-form active" id="reset-password-form">
                <div class="form-group">
                    <label class="form-label" for="password_1"><?php esc_html_e('New Password', 'wp-travel'); ?></label>
                    <input id="password_1" class="form-input" type="password" name="password_1" placeholder="<?php esc_attr_e('Enter new password', 'wp-travel'); ?>" required />
                    <div class="password-strength-meter" style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--muted-foreground);">
                        <div class="password-strength-label"><?php esc_html_e('Password strength:', 'wp-travel'); ?> <span id="password-strength">weak</span></div>
                        <div class="password-strength-bar" style="height: 4px; background-color: #e2e2e2; border-radius: 2px; margin-top: 0.25rem;">
                            <div id="strength-meter" style="height: 100%; width: 0; border-radius: 2px; transition: all 0.3s;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password_2"><?php esc_html_e('Confirm New Password', 'wp-travel'); ?></label>
                    <input id="password_2" class="form-input" type="password" name="password_2" placeholder="<?php esc_attr_e('Confirm new password', 'wp-travel'); ?>" required />
                    <div id="password-match-message" style="margin-top: 0.5rem; font-size: 0.75rem; display: none;"></div>
                </div>

                <div class="auth-info-box" style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background-color: rgba(245, 122, 69, 0.1); border-radius: var(--radius); border-left: 4px solid var(--primary);">
                    <p style="margin: 0; font-size: 0.875rem; color: var(--primary);">
                        <?php esc_html_e('Strong passwords include a mix of uppercase letters, lowercase letters, numbers, and symbols.', 'wp-travel'); ?>
                    </p>
                </div>
                
                <input type="hidden" name="reset_key" value="<?php echo esc_attr($reset_key); ?>" />
                <input type="hidden" name="reset_login" value="<?php echo esc_attr($reset_login); ?>" />
                <?php wp_nonce_field('reset-password', 'wp-travel-reset-password-nonce'); ?>
                
                <button type="submit" name="wp-travel-reset-password" value="<?php esc_attr_e('Save Password', 'wp-travel'); ?>" class="auth-button">
                    <i class='bx bx-lock-open'></i>
                    <?php esc_html_e('Set New Password', 'wp-travel'); ?>
                </button>
                
                <div class="auth-links" style="justify-content: center; margin-top: 1.5rem;">
                    <a href="<?php echo esc_url(wptravel_get_page_permalink('wp-travel-dashboard')); ?>" class="auth-link">
                        <i class='bx bx-arrow-back'></i>
                        <?php esc_html_e('Back to Login', 'wp-travel'); ?>
                    </a>
                </div>
            </form>
            
            <!-- Password Tips Section -->
            <div class="social-proof">
                <h4 class="social-proof-heading"><?php esc_html_e('Password Tips', 'wp-travel'); ?></h4>
                <ul style="text-align: left; font-size: 0.875rem; color: var(--muted-foreground); padding-left: 1.5rem; margin: 0;">
                    <li style="margin-bottom: 0.5rem;"><?php esc_html_e('Use at least 8 characters', 'wp-travel'); ?></li>
                    <li style="margin-bottom: 0.5rem;"><?php esc_html_e('Include numbers, symbols, and both uppercase and lowercase letters', 'wp-travel'); ?></li>
                    <li style="margin-bottom: 0.5rem;"><?php esc_html_e('Avoid using personal information or common words', 'wp-travel'); ?></li>
                    <li><?php esc_html_e('Use different passwords for different accounts', 'wp-travel'); ?></li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const password1 = document.getElementById('password_1');
        const password2 = document.getElementById('password_2');
        const form = document.getElementById('reset-password-form');
        const strengthMeter = document.getElementById('strength-meter');
        const strengthLabel = document.getElementById('password-strength');
        const matchMessage = document.getElementById('password-match-message');
        
        // Check password strength
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Character type checks
            if (/[a-z]/.test(password)) strength += 1; // lowercase
            if (/[A-Z]/.test(password)) strength += 1; // uppercase
            if (/[0-9]/.test(password)) strength += 1; // numbers
            if (/[^A-Za-z0-9]/.test(password)) strength += 1; // special chars
            
            return strength;
        }
        
        function updateStrengthMeter() {
            const password = password1.value;
            const strength = checkPasswordStrength(password);
            
            // Update strength meter visuals
            switch(strength) {
                case 0:
                case 1:
                    strengthMeter.style.width = '20%';
                    strengthMeter.style.backgroundColor = '#ff4d4f';
                    strengthLabel.textContent = '<?php echo esc_js(__('very weak', 'wp-travel')); ?>';
                    strengthLabel.style.color = '#ff4d4f';
                    break;
                case 2:
                    strengthMeter.style.width = '40%';
                    strengthMeter.style.backgroundColor = '#ff7a45';
                    strengthLabel.textContent = '<?php echo esc_js(__('weak', 'wp-travel')); ?>';
                    strengthLabel.style.color = '#ff7a45';
                    break;
                case 3:
                    strengthMeter.style.width = '60%';
                    strengthMeter.style.backgroundColor = '#ffa940';
                    strengthLabel.textContent = '<?php echo esc_js(__('medium', 'wp-travel')); ?>';
                    strengthLabel.style.color = '#ffa940';
                    break;
                case 4:
                    strengthMeter.style.width = '80%';
                    strengthMeter.style.backgroundColor = '#52c41a';
                    strengthLabel.textContent = '<?php echo esc_js(__('strong', 'wp-travel')); ?>';
                    strengthLabel.style.color = '#52c41a';
                    break;
                case 5:
                case 6:
                    strengthMeter.style.width = '100%';
                    strengthMeter.style.backgroundColor = '#389e0d';
                    strengthLabel.textContent = '<?php echo esc_js(__('very strong', 'wp-travel')); ?>';
                    strengthLabel.style.color = '#389e0d';
                    break;
            }
        }
        
        // Check if passwords match
        function checkPasswordMatch() {
            if (password2.value === '') {
                matchMessage.style.display = 'none';
                password2.style.borderColor = '';
                return;
            }
            
            if (password1.value === password2.value) {
                matchMessage.style.display = 'block';
                matchMessage.textContent = '<?php echo esc_js(__('Passwords match', 'wp-travel')); ?>';
                matchMessage.style.color = '#52c41a';
                password2.style.borderColor = '#52c41a';
            } else {
                matchMessage.style.display = 'block';
                matchMessage.textContent = '<?php echo esc_js(__('Passwords do not match', 'wp-travel')); ?>';
                matchMessage.style.color = '#ff4d4f';
                password2.style.borderColor = '#ff4d4f';
            }
        }
        
        // Event listeners
        password1.addEventListener('keyup', updateStrengthMeter);
        password1.addEventListener('keyup', checkPasswordMatch);
        password2.addEventListener('keyup', checkPasswordMatch);
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const p1 = password1.value;
            const p2 = password2.value;
            
            let isValid = true;
            
            // Check if both fields have values
            if (!p1 || !p2) {
                isValid = false;
                if (!p1) {
                    password1.style.borderColor = 'var(--destructive)';
                }
                if (!p2) {
                    password2.style.borderColor = 'var(--destructive)';
                }
            } 
            // Check if passwords match
            else if (p1 !== p2) {
                isValid = false;
                password1.style.borderColor = 'var(--destructive)';
                password2.style.borderColor = 'var(--destructive)';
                matchMessage.style.display = 'block';
                matchMessage.textContent = '<?php echo esc_js(__('Passwords do not match', 'wp-travel')); ?>';
                matchMessage.style.color = '#ff4d4f';
            }
            // Check password strength
            else if (checkPasswordStrength(p1) < 3) {
                isValid = false;
                password1.style.borderColor = 'var(--destructive)';
                
                let errorMsg = password1.parentNode.querySelector('.input-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'input-error';
                    errorMsg.style.color = 'var(--destructive)';
                    errorMsg.style.fontSize = '0.75rem';
                    errorMsg.style.marginTop = '0.25rem';
                    password1.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = '<?php echo esc_js(__('Please choose a stronger password', 'wp-travel')); ?>';
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html> 
<?php
/**
 * Viator Setup Helper
 * Quick setup script to enable Viator integration for testing
 * 
 * @package PriyanshTours
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if running standalone
    require_once('../../../wp-config.php');
    require_once('../../../wp-load.php');
}

// Only allow this to run if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied. Admin privileges required.');
}

// Handle form submission
if (isset($_POST['enable_viator'])) {
    update_option('priyanshtours_viator_enabled', 1);
    update_option('priyanshtours_viator_supplier_id', '5597044');
    update_option('priyanshtours_viator_api_key', 'demo-key-for-testing');
    
    $message = 'Viator integration enabled for testing! You can now see how the hybrid display works.';
    $message_type = 'success';
}

if (isset($_POST['disable_viator'])) {
    update_option('priyanshtours_viator_enabled', 0);
    $message = 'Viator integration disabled.';
    $message_type = 'info';
}

$viator_enabled = get_option('priyanshtours_viator_enabled', 0);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Viator Setup Helper</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #005a87; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .message { padding: 15px; border-radius: 4px; margin: 15px 0; }
        .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.info { background: #cce5ff; color: #004085; border: 1px solid #bee5eb; }
        .status { padding: 15px; border-radius: 4px; margin: 15px 0; font-weight: bold; }
        .status.enabled { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status.disabled { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info-box { background: #e7f3ff; padding: 15px; border-left: 4px solid #007cba; margin: 15px 0; }
        .step { margin: 20px 0; padding: 15px; border: 1px solid #dee2e6; border-radius: 8px; }
        .step h3 { margin-top: 0; color: #007cba; }
    </style>
</head>
<body>
    <h1>🛠️ Viator Integration Setup</h1>
    
    <?php if (isset($message)) : ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <div class="status <?php echo $viator_enabled ? 'enabled' : 'disabled'; ?>">
        <strong>Current Status:</strong> 
        <?php echo $viator_enabled ? '✅ Viator Integration Enabled' : '❌ Viator Integration Disabled'; ?>
    </div>
    
    <div class="form-section">
        <h2>Quick Setup</h2>
        
        <?php if (!$viator_enabled) : ?>
            <form method="post">
                <p>Enable Viator integration with demo settings to test how the hybrid display works:</p>
                <button type="submit" name="enable_viator" class="btn btn-success">
                    🚀 Enable Viator Integration (Demo Mode)
                </button>
            </form>
            
            <div class="info-box">
                <strong>📝 What this does:</strong>
                <ul>
                    <li>Enables the Viator integration feature</li>
                    <li>Sets your supplier ID to 5597044 (from your CSV)</li>
                    <li>Uses a demo API key for testing</li>
                    <li>Shows source filtering on tours page</li>
                    <li>Allows you to create sample Viator tours</li>
                </ul>
            </div>
        <?php else : ?>
            <form method="post">
                <p>Viator integration is currently enabled. You can disable it if needed:</p>
                <button type="submit" name="disable_viator" class="btn btn-danger">
                    ❌ Disable Viator Integration
                </button>
            </form>
        <?php endif; ?>
    </div>
    
    <div class="form-section">
        <h2>📋 Next Steps</h2>
        
        <div class="step">
            <h3>1. Test the Demo Tour</h3>
            <p>Create a sample Viator tour to see how it appears on your site:</p>
            <a href="viator-demo.php" class="btn" target="_blank">Open Demo Page</a>
        </div>
        
        <div class="step">
            <h3>2. Check Tours Page</h3>
            <p>Visit your tours page to see the hybrid display with source filtering:</p>
            <a href="<?php echo home_url('/itinerary/'); ?>" class="btn" target="_blank">View Tours Page</a>
        </div>
        
        <div class="step">
            <h3>3. Admin Settings</h3>
            <p>Configure full settings in WordPress admin:</p>
            <a href="<?php echo admin_url('themes.php?page=viator-integration'); ?>" class="btn" target="_blank">Viator Settings</a>
        </div>
        
        <div class="step">
            <h3>4. Get Real API Key</h3>
            <p>For live integration, you'll need a Viator Partner API key:</p>
            <ul>
                <li>Contact Viator Partner Support</li>
                <li>Request API access for your supplier ID: <strong>5597044</strong></li>
                <li>Replace demo key with real API key in settings</li>
            </ul>
        </div>
    </div>
    
    <div class="form-section">
        <h2>🎯 Current Configuration</h2>
        <ul>
            <li><strong>Supplier ID:</strong> <?php echo get_option('priyanshtours_viator_supplier_id', '5597044'); ?></li>
            <li><strong>API Key:</strong> <?php echo $viator_enabled ? '***demo-key***' : 'Not set'; ?></li>
            <li><strong>Integration Status:</strong> <?php echo $viator_enabled ? 'Enabled' : 'Disabled'; ?></li>
        </ul>
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo home_url(); ?>" class="btn">← Back to Website</a>
    </div>
</body>
</html> 
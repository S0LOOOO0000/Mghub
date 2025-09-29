<?php
// Favicon Include File - Optimized for clarity and better browser support
$base_path = (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || 
              strpos($_SERVER['REQUEST_URI'], '/client/') !== false || 
              strpos($_SERVER['REQUEST_URI'], '/client-mghub/') !== false || 
              strpos($_SERVER['REQUEST_URI'], '/client-spa/') !== false) ? '../' : '';
?>
<!-- Favicon and App Icons - Optimized order for better display -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base_path; ?>images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base_path; ?>images/favicon/favicon-16x16.png">
<link rel="icon" type="image/x-icon" href="<?php echo $base_path; ?>images/favicon/favicon.ico">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $base_path; ?>images/favicon/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_path; ?>images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo $base_path; ?>images/favicon/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="<?php echo $base_path; ?>images/favicon/android-chrome-512x512.png">
<!-- Enhanced Meta Tags for Better Display -->
<meta name="theme-color" content="#0072ff">
<meta name="msapplication-TileColor" content="#0072ff">
<meta name="msapplication-config" content="<?php echo $base_path; ?>images/favicon/browserconfig.xml">

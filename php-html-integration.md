# PHP-HTML Integration Documentation

## Overview

This document explains how the PHP pages in the root directory are linked to the HTML pages in the `pages/` directory. The integration allows users to access the HTML content through the PHP framework, providing a consistent navigation and header/footer across the site.

## Integration Approach

The integration uses PHP wrapper files that embed the HTML content using iframes. This approach has several advantages:

1. It preserves the original HTML content without modification
2. It allows the HTML pages to maintain their own styling
3. It provides a consistent navigation and header/footer across the site

## PHP Wrapper Files

Two PHP wrapper files have been created:

1. `colloque2010.php` - Links to the HTML pages in the `pages/colloque2010/` directory
2. `jdm.php` - Links to the HTML pages in the `pages/JDM/` directory

Each wrapper file:
- Includes the header and footer from the PHP framework
- Provides links to all the HTML pages in the respective directory
- Embeds the main HTML page using an iframe

## Navigation Updates

The navigation links in the header and footer have been updated to include links to the new PHP wrapper files. This ensures that users can access the HTML content through the PHP framework's navigation menu.

## How to Add More HTML Pages

If you need to integrate more HTML pages with the PHP framework, follow these steps:

1. Create a new PHP wrapper file in the root directory (e.g., `new-html-content.php`)
2. Include the header and footer from the PHP framework
3. Add links to all the HTML pages in the respective directory
4. Embed the main HTML page using an iframe
5. Update the navigation links in the header and footer to include a link to the new PHP wrapper file

### Example PHP Wrapper File

```php
<?php
// Include configuration file
require_once 'includes/config.php';

// Set page-specific variables
$page_title = "Your Page Title";
$additional_css = [];
$additional_js = [];

// Include header
include_once 'includes/header.php';
?>

<div class="content-area">
    <h2 class="page-title">Your Page Title</h2>
    
    <p>Description of the content.</p>
    
    <p>For more information, click on one of the links below:</p>
    
    <ul class="html-links">
        <li><a href="pages/your-directory/index.html" target="_blank">Page 1</a></li>
        <li><a href="pages/your-directory/page2.html" target="_blank">Page 2</a></li>
        <!-- Add more links as needed -->
    </ul>
    
    <div class="iframe-container">
        <iframe src="pages/your-directory/index.html" width="100%" height="600" frameborder="0"></iframe>
    </div>
</div>

<style>
    .html-links {
        margin-bottom: 20px;
    }
    
    .html-links li {
        margin-bottom: 5px;
    }
    
    .iframe-container {
        position: relative;
        overflow: hidden;
        padding-top: 56.25%;
        margin-top: 30px;
    }
    
    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 600px;
        border: 1px solid #ddd;
    }
</style>

<?php
// Include footer
include_once 'includes/footer.php';
?>
```

## Maintenance

When updating the HTML pages, no changes are needed to the PHP wrapper files unless you add new HTML pages that should be linked from the wrapper file.

If you add new directories with HTML pages, you'll need to create new PHP wrapper files for them and update the navigation links in the header and footer.
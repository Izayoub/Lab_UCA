// Common JavaScript file for all colloque2010 pages
// This script dynamically loads the fix-css.js script

// Create a new script element
var script = document.createElement('script');
script.src = 'fix-css.js';
script.type = 'text/javascript';

// Add it to the head of the document
document.head.appendChild(script);
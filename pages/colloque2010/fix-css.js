// JavaScript to dynamically add the CSS link to the HTML pages
document.addEventListener('DOMContentLoaded', function() {
    // Create a new link element
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = 'style.css';
    
    // Add it to the head of the document
    document.head.appendChild(link);
    
    // Apply some basic styling to improve the appearance
    document.body.style.fontFamily = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    document.body.style.lineHeight = "1.6";
    document.body.style.margin = "0";
    document.body.style.padding = "0";
    document.body.style.backgroundColor = "#F5E2E2";
    document.body.style.color = "#333333";
    
    // Style all links
    var links = document.getElementsByTagName('a');
    for (var i = 0; i < links.length; i++) {
        links[i].style.color = "#A51029";
        links[i].style.textDecoration = "none";
    }
    
    // Style the menu
    var menuItems = document.querySelectorAll('.markermenu ul li a');
    for (var i = 0; i < menuItems.length; i++) {
        menuItems[i].style.display = "block";
        menuItems[i].style.padding = "8px 10px";
        menuItems[i].style.backgroundColor = "#F5E2E2";
        menuItems[i].style.color = "#A51029";
        menuItems[i].style.textDecoration = "none";
        menuItems[i].style.borderLeft = "4px solid #A51029";
        menuItems[i].style.transition = "all 0.3s ease";
    }
    
    // Add hover effect to menu items
    for (var i = 0; i < menuItems.length; i++) {
        menuItems[i].addEventListener('mouseover', function() {
            this.style.backgroundColor = "#A51029";
            this.style.color = "white";
            this.style.borderLeft = "4px solid #660033";
        });
        
        menuItems[i].addEventListener('mouseout', function() {
            this.style.backgroundColor = "#F5E2E2";
            this.style.color = "#A51029";
            this.style.borderLeft = "4px solid #A51029";
        });
    }
    
    // Make images responsive
    var images = document.getElementsByTagName('img');
    for (var i = 0; i < images.length; i++) {
        images[i].style.maxWidth = "100%";
        images[i].style.height = "auto";
    }
    
    // Style tables
    var tables = document.getElementsByTagName('table');
    for (var i = 0; i < tables.length; i++) {
        tables[i].style.borderCollapse = "collapse";
        tables[i].style.width = "100%";
    }
    
    // Style table cells
    var cells = document.getElementsByTagName('td');
    for (var i = 0; i < cells.length; i++) {
        cells[i].style.padding = "8px";
    }
});
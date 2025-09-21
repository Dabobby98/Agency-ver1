// Language switching functionality
function switchLanguage(lang) {
    if (lang === 'vi') {
        // Switch to Vietnamese
        if (window.location.pathname.includes('/vi/')) {
            // Already in Vietnamese folder
            return;
        }
        
        // Always redirect to Vietnamese homepage for now (until all pages are translated)
        window.location.href = './vi/index.html';
    } else {
        // Switch to English
        if (!window.location.pathname.includes('/vi/')) {
            // Already in English version
            return;
        }
        
        const currentPath = window.location.pathname;
        const currentFile = currentPath.split('/').pop() || 'index.html';
        
        // Redirect to English version (parent directory)
        window.location.href = '../' + currentFile;
    }
}

// Detect current language and load appropriate header/footer
function getCurrentLanguage() {
    return window.location.pathname.includes('/vi/') ? 'vi' : 'en';
}

// Load header and footer based on current language
function loadLanguageComponents() {
    const currentLang = getCurrentLanguage();
    
    let headerFile, footerFile;
    
    if (currentLang === 'vi') {
        headerFile = './header-vi.html';
        footerFile = './footer-vi.html';
    } else {
        headerFile = './header.html';
        footerFile = './footer.html';
    }
    
    // Load components
    Promise.all([
        fetch(headerFile).then(res => res.text()),
        fetch(footerFile).then(res => res.text()),
        fetch("./sidebar.html").then(res => res.text()),
        fetch("./search-form.html").then(res => res.text())
    ])
    .then(([headerHTML, footerHTML, sidebarHTML, searchHTML]) => {
        $("#header").html(headerHTML);
        $("#footer").html(footerHTML);
        $("#sidebar").html(sidebarHTML);
        $("#edit-sidebar").html(sidebarHTML);
        $("#search-form-container").html(searchHTML);
        
        // Initialize navigation after loading (with guard)
        if (typeof initNavLink === 'function') {
            initNavLink();
        }
        
        // Initialize other components
        if (typeof initThemeSwitch === 'function') {
            initThemeSwitch();
        }
        if (typeof initSubmitContact === 'function') {
            initSubmitContact();
        }
        if (typeof initSubmitNewsletter === 'function') {
            initSubmitNewsletter();
        }
        
        // Dispatch event that components are loaded
        dispatchComponentsLoaded();
    })
    .catch(error => {
        console.error('Error loading language components:', error);
        
        // Fallback to default files
        Promise.all([
            fetch("./header.html").then(res => res.text()),
            fetch("./footer.html").then(res => res.text()),
            fetch("./sidebar.html").then(res => res.text()),
            fetch("./search-form.html").then(res => res.text())
        ])
        .then(([headerHTML, footerHTML, sidebarHTML, searchHTML]) => {
            $("#header").html(headerHTML);
            $("#footer").html(footerHTML);
            $("#sidebar").html(sidebarHTML);
            $("#edit-sidebar").html(sidebarHTML);
            $("#search-form-container").html(searchHTML);
            initNavLink();
        });
    });
}

// Dispatch event when components are loaded
function dispatchComponentsLoaded() {
    const event = new CustomEvent('componentsLoaded');
    document.dispatchEvent(event);
}

// Initialize language system
// Wait for jQuery to be available
function initLanguageSystem() {
    if (typeof $ !== 'undefined') {
        loadLanguageComponents();
    } else {
        setTimeout(initLanguageSystem, 100);
    }
}

// Start the initialization
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLanguageSystem);
} else {
    initLanguageSystem();
}
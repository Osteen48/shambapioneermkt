var menuIcon = document.querySelector(".menu-icon");
var sidebar = document.querySelector(".sidebar");
var container = document.querySelector(".container");

menuIcon.onclick = function(){
    sidebar.classList.toggle("small-sidebar");
    container.classList.toggle("large-container");
}




function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(function(sec) {
        sec.classList.remove('active-section');
        sec.style.display = 'none';
        sec.style.opacity = 0;
    });
    // Show selected section with fade-in
    var activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
        setTimeout(function() {
            activeSection.classList.add('active-section');
            activeSection.style.opacity = 1;
        }, 10);
    }

    // Remove active class from all sidebar links
    document.querySelectorAll('.shortcut-links a').forEach(function(link) {
        link.classList.remove('active');
        link.removeAttribute('aria-current');
    });
    // Add active class to clicked link
    var btnId = 'btn-' + sectionId;
    var activeBtn = document.getElementById(btnId);
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.setAttribute('aria-current', 'page');
    }
}

// Logout confirmation
function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        showSection('logout');
    }
}











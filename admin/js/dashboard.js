document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const page = params.get("page") || "dashboard";

    document.querySelectorAll(".page").forEach(function (pageDiv) {
        pageDiv.style.display = "none";
    });

    const activePage = document.getElementById(page);
    if (activePage) {
        activePage.style.display = "block";
    } else {
        const dashboardPage = document.getElementById("dashboard");
        if (dashboardPage) dashboardPage.style.display = "block";
    }

    const menuItems = document.querySelectorAll(".menu");
    menuItems.forEach(function (menu) {
        menu.classList.remove("active");
    });

    const menuIndex = {
        dashboard: 0,
        users: 1,
        personnel: 2,
        article: 3,
        inventory: 4,
        gallery: 5,
        validation: 6,
        reports: 7,
        settings: 8
    };

    if (menuIndex[page] !== undefined && menuItems[menuIndex[page]]) {
        menuItems[menuIndex[page]].classList.add("active");
    } else if (menuItems[0]) {
        menuItems[0].classList.add("active");
    }
});

function showPage(pageId) {
    window.location.href = "dashboard.php?page=" + pageId;
}
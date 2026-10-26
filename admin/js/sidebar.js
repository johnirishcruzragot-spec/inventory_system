function toggleSidebar() {

    const sidebar = document.querySelector(".sidebar");
    const main = document.querySelector(".main");

    sidebar.classList.toggle("collapsed");

    if (sidebar.classList.contains("collapsed")) {
        main.style.marginLeft = "80px";
        document.querySelector(".topbar").style.left = "100px";
    } else {
        main.style.marginLeft = "260px";
        document.querySelector(".topbar").style.left = "280px";
    }

}
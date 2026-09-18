document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const sidebarOverlay = document.getElementById("sidebarOverlay");

  if (!menuToggle || !sidebar || !sidebarOverlay) {
    return;
  }

  function closeSidebar() {
    sidebar.classList.remove("show");
    sidebarOverlay.classList.remove("show");
    menuToggle.setAttribute("aria-expanded", "false");
  }

  menuToggle.addEventListener("click", function () {
    const isOpen = sidebar.classList.toggle("show");
    sidebarOverlay.classList.toggle("show", isOpen);
    menuToggle.setAttribute("aria-expanded", String(isOpen));
  });

  sidebarOverlay.addEventListener("click", closeSidebar);

  sidebar.querySelectorAll("a").forEach(function (link) {
    link.addEventListener("click", closeSidebar);
  });

  let touchStartY = 0;

  sidebar.addEventListener(
    "touchstart",
    function (event) {
      touchStartY = event.touches[0].clientY;
    },
    { passive: true },
  );

  sidebar.addEventListener(
    "touchmove",
    function (event) {
      const touchY = event.touches[0].clientY;
      const scrollDelta = touchStartY - touchY;

      sidebar.scrollTop += scrollDelta;
      touchStartY = touchY;
      event.preventDefault();
    },
    { passive: false },
  );
});

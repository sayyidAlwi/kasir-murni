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

const sidebarToggle = document.getElementById("sidebarToggle");
const sidebar = document.getElementById("sidebar");
if (sidebarToggle && sidebar) {
  sidebarToggle.addEventListener("click", function () {
    sidebar.classList.toggle("show");
  });
}
const searchInput = document.getElementById("searchInput");
if (searchInput) {
  searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll("#salesTable tr");
    rows.forEach(function (row) {
      row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
    });
  });
}
function filterData() {
  const from = document.getElementById("dateFrom").value;
  const to = document.getElementById("dateTo").value;
  if (!from && !to) {
    alert("Silakan pilih tanggal terlebih dahulu.");
    return;
  }
  alert("Filter tanggal: " + (from || "-") + " sampai " + (to || "-"));
}

function initializeTheme() {
  const themeselect = document.getElementById("themeselect");
  const bodyTheme = document.body.dataset.theme || localStorage.getItem("theme") || "light";
  const savedtheme = ["light", "dark"].includes(bodyTheme) ? bodyTheme : "light";

  document.body.classList.remove("light", "dark");
  document.body.classList.add(savedtheme);
  document.body.dataset.theme = savedtheme;
  localStorage.setItem("theme", savedtheme);

  if (!themeselect) {
    return;
  }

  themeselect.value = savedtheme;
  themeselect.addEventListener("change", function () {
    const theme = this.value;
    document.body.classList.remove("light", "dark");
    document.body.classList.add(theme);
    document.body.dataset.theme = theme;
    localStorage.setItem("theme", theme);
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeTheme);
} else {
  initializeTheme();
}
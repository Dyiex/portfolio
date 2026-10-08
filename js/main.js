// Footer year
document.getElementById("year").textContent = new Date().getFullYear();

// Close the mobile menu after choosing a link
document.querySelectorAll("#navLinks .nav-link, #navLinks .btn").forEach((link) => {
  link.addEventListener("click", () => {
    const menu = document.getElementById("navLinks");
    if (menu.classList.contains("show")) bootstrap.Collapse.getOrCreateInstance(menu).hide();
  });
});

// Light / dark theme toggle (remembers the choice)
(function () {
  var root = document.documentElement;
  var btn = document.getElementById("themeToggle");
  var icon = btn.querySelector("i");

  function apply(theme) {
    root.setAttribute("data-bs-theme", theme);
    var dark = theme === "dark";
    icon.className = dark ? "bi bi-sun-fill" : "bi bi-moon-stars-fill";
    btn.setAttribute("aria-label", dark ? "Switch to light mode" : "Switch to dark mode");
  }

  apply(root.getAttribute("data-bs-theme") || "light");

  btn.addEventListener("click", function () {
    var next = root.getAttribute("data-bs-theme") === "dark" ? "light" : "dark";
    apply(next);
    try { localStorage.setItem("theme", next); } catch (e) {}
  });
})();

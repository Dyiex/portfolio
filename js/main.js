// Footer year
document.getElementById("year").textContent = new Date().getFullYear();

// Close the mobile menu after choosing a link
document.querySelectorAll("#navLinks .nav-link, #navLinks .btn").forEach((link) => {
  link.addEventListener("click", () => {
    const menu = document.getElementById("navLinks");
    if (menu.classList.contains("show")) bootstrap.Collapse.getOrCreateInstance(menu).hide();
  });
});

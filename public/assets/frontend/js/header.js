 function toggleMenu() {
    document.getElementById("mobileMenu").classList.toggle("open");
  }

  function toggleDropdown(e) {
    e.preventDefault();
    const parent = e.currentTarget.parentElement;
    parent.classList.toggle("open");
  }

  // ✅ Listen for window resize
  window.addEventListener("resize", function () {
    const mobileMenu = document.getElementById("mobileMenu");

    // Agar screen desktop size se barhi ho to mobile menu band karo
    if (window.innerWidth > 991) {
      mobileMenu.classList.remove("open");
    }
  });
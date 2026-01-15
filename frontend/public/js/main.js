// Basic client-side behavior: nav toggle and contact form stub handling.

document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector("[data-nav-toggle]");
  const navLinks = document.querySelector("[data-nav-links]");

  if (navToggle && navLinks) {
    navToggle.addEventListener("click", () => {
      const isOpen = navLinks.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    // Close menu on Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && navLinks.classList.contains("open")) {
        navLinks.classList.remove("open");
        navToggle.setAttribute("aria-expanded", "false");
        navToggle.focus();
      }
    });
  }

  // Dropdown toggle functionality
  const dropdownTriggers = document.querySelectorAll(".nav-dropdown-trigger, .nav-dropdown-caret-btn");
  
  dropdownTriggers.forEach((trigger) => {
    const dropdown = trigger.closest(".nav-dropdown");
    const menu = dropdown?.querySelector(".nav-dropdown-menu");
    const menuLinks = menu?.querySelectorAll("a");
    
    // Click handler
    trigger.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleDropdown(dropdown, trigger);
    });

    // Keyboard handlers
    trigger.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        toggleDropdown(dropdown, trigger);
      } else if (e.key === "Escape") {
        closeDropdown(dropdown, trigger);
      } else if (e.key === "ArrowDown" && menu && menuLinks && menuLinks.length > 0) {
        e.preventDefault();
        if (!dropdown.classList.contains("open")) {
          openDropdown(dropdown, trigger);
        }
        menuLinks[0].focus();
      }
    });

    // Keyboard navigation for menu items
    if (menuLinks && menuLinks.length > 0) {
      menuLinks.forEach((link, index) => {
        link.addEventListener("keydown", (e) => {
          if (e.key === "Escape") {
            e.preventDefault();
            closeDropdown(dropdown, trigger);
            trigger.focus();
          } else if (e.key === "ArrowDown") {
            e.preventDefault();
            const nextIndex = (index + 1) % menuLinks.length;
            menuLinks[nextIndex].focus();
          } else if (e.key === "ArrowUp") {
            e.preventDefault();
            const prevIndex = index === 0 ? menuLinks.length - 1 : index - 1;
            menuLinks[prevIndex].focus();
          } else if (e.key === "Home") {
            e.preventDefault();
            menuLinks[0].focus();
          } else if (e.key === "End") {
            e.preventDefault();
            menuLinks[menuLinks.length - 1].focus();
          }
        });
      });
    }
  });

  function toggleDropdown(dropdown, trigger) {
    if (!dropdown || !trigger) return;
    
    const isOpen = dropdown.classList.contains("open");
    
    // Close all other dropdowns
    document.querySelectorAll(".nav-dropdown").forEach((d) => {
      if (d !== dropdown) {
        const btn = d.querySelector("button");
        closeDropdown(d, btn);
      }
    });
    
    // Toggle current dropdown
    if (isOpen) {
      closeDropdown(dropdown, trigger);
    } else {
      openDropdown(dropdown, trigger);
    }
  }

  function openDropdown(dropdown, trigger) {
    if (!dropdown || !trigger) return;
    dropdown.classList.add("open");
    trigger.setAttribute("aria-expanded", "true");
  }

  function closeDropdown(dropdown, trigger) {
    if (!dropdown || !trigger) return;
    dropdown.classList.remove("open");
    trigger.setAttribute("aria-expanded", "false");
  }

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".nav-dropdown")) {
      document.querySelectorAll(".nav-dropdown").forEach((dropdown) => {
        const btn = dropdown.querySelector("button");
        closeDropdown(dropdown, btn);
      });
    }
  });

  // Close mobile menu when clicking a link
  if (navLinks) {
    const links = navLinks.querySelectorAll("a");
    links.forEach((link) => {
      link.addEventListener("click", () => {
        // Only close on mobile (when menu is open)
        if (window.innerWidth <= 720 && navLinks.classList.contains("open")) {
          navLinks.classList.remove("open");
          if (navToggle) {
            navToggle.setAttribute("aria-expanded", "false");
          }
        }
      });
    });
  }

  const contactForm = document.querySelector("[data-contact-form]");
  const alertBox = document.querySelector("[data-contact-alert]");

  if (contactForm && alertBox) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault();

      const formData = new FormData(contactForm);
      const name = formData.get("name")?.toString().trim();
      const email = formData.get("email")?.toString().trim();
      const subject = formData.get("subject")?.toString().trim();
      const message = formData.get("message")?.toString().trim();

      if (!name || !email || !subject || !message) {
        alertBox.textContent =
          "Please fill in all required fields before submitting.";
        alertBox.className = "alert alert-error";
        alertBox.hidden = false;
        return;
      }

      // Placeholder: in a real implementation this would call /api/contact.
      alertBox.textContent =
        "Thank you for reaching out. Your inquiry has been recorded (demo).";
      alertBox.className = "alert alert-success";
      alertBox.hidden = false;

      contactForm.reset();
    });
  }
});



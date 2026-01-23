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

  // ============================================
  // AppImage Component: Image Loading Handler
  // ============================================
  
  /**
   * Handle image load completion
   * Hides skeleton and fades in the image
   */
  window.handleImageLoad = function(imageId) {
    const image = document.getElementById(imageId);
    if (!image) return;
    
    const container = image.closest('.app-image-container');
    if (!container) return;
    
    const skeleton = container.querySelector('[data-skeleton]');
    
    // Mark image as loaded
    image.classList.add('loaded');
    
    // Hide skeleton after a brief delay to ensure smooth transition
    if (skeleton) {
      // Wait for image to be decoded for smoother transition
      if (image.decode) {
        image.decode().then(() => {
          skeleton.classList.add('hidden');
        }).catch(() => {
          // Fallback if decode fails
          setTimeout(() => {
            skeleton.classList.add('hidden');
          }, 100);
        });
      } else {
        // Fallback for browsers without decode()
        setTimeout(() => {
          skeleton.classList.add('hidden');
        }, 150);
      }
    }
  };
  
  // Handle images that are already loaded (cached)
  document.querySelectorAll('.app-image').forEach((img) => {
    if (img.complete && img.naturalHeight !== 0) {
      window.handleImageLoad(img.id);
    }
  });
  
  // Handle background image loading for banners
  document.querySelectorAll('.home-banner-background, .about-banner-background').forEach((bg) => {
    const bgImage = window.getComputedStyle(bg).backgroundImage;
    if (!bgImage || bgImage === 'none') return;
    
    // Extract URL from background-image
    const urlMatch = bgImage.match(/url\(['"]?([^'"]+)['"]?\)/);
    if (!urlMatch) return;
    
    const img = new Image();
    img.onload = () => {
      bg.classList.add('loaded');
    };
    img.src = urlMatch[1];
    
    // If already cached, mark as loaded immediately
    if (img.complete) {
      bg.classList.add('loaded');
    }
  });
  
  // Preload images on link hover for common navigation paths
  // Uses data attributes set in Blade templates for route-specific preloading
  document.querySelectorAll('a[data-preload-image]').forEach((link) => {
    const imageSrc = link.getAttribute('data-preload-image');
    if (!imageSrc) return;
    
    let preloadLink = null;
    
    link.addEventListener('mouseenter', () => {
      if (!preloadLink) {
        preloadLink = document.createElement('link');
        preloadLink.rel = 'preload';
        preloadLink.as = 'image';
        preloadLink.href = imageSrc;
        document.head.appendChild(preloadLink);
      }
    }, { once: true });
  });
});



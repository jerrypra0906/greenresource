// Basic client-side behavior: nav toggle and contact form stub handling.

document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.querySelector("[data-nav-toggle]");
  const navLinks = document.querySelector("[data-nav-links]");

  if (navToggle && navLinks) {
    navToggle.addEventListener("click", () => {
      navLinks.classList.toggle("open");
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



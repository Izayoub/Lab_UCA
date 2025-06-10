/**
 * Dashboard JavaScript file for LIRE-RMD website
 */

document.addEventListener("DOMContentLoaded", () => {
  // Initialize TinyMCE editors
  initTinyMCE()

  // Initialize dashboard navigation
  initDashboardNav()

  // Initialize dashboard tabs
  initDashboardTabs()

  // Initialize form validation
  initFormValidation()

  // Initialize image preview
  initImagePreview()

  // Initialize confirmation dialogs
  initConfirmDialogs()
})

/**
 * Initialize TinyMCE editors
 */
function initTinyMCE() {
  if (typeof tinymce !== "undefined") {
    tinymce.init({
      selector: ".tinymce-editor",
      height: 300,
      menubar: false,
      plugins: [
        "advlist",
        "autolink",
        "lists",
        "link",
        "image",
        "charmap",
        "preview",
        "anchor",
        "searchreplace",
        "visualblocks",
        "code",
        "fullscreen",
        "insertdatetime",
        "media",
        "table",
        "help",
        "wordcount",
      ],
      toolbar:
        "undo redo | formatselect | " +
        "bold italic backcolor | alignleft aligncenter " +
        "alignright alignjustify | bullist numlist outdent indent | " +
        "removeformat | help",
      content_style:
        "body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }",
      branding: false,
      promotion: false,
    })
  }
}

/**
 * Initialize dashboard navigation
 */
function initDashboardNav() {
  const navItems = document.querySelectorAll(".dashboard-nav-item")
  const sections = document.querySelectorAll(".dashboard-section")

  if (navItems.length > 0 && sections.length > 0) {
    // Show first section by default
    sections[0].classList.remove("hidden")
    navItems[0].classList.add("active")

    navItems.forEach((item) => {
      item.addEventListener("click", (e) => {
        e.preventDefault()

        // Get target section ID
        const targetId = item.getAttribute("href").substring(1)
        const targetSection = document.getElementById(targetId)

        // Hide all sections
        sections.forEach((section) => {
          section.classList.add("hidden")
        })

        // Remove active class from all nav items
        navItems.forEach((navItem) => {
          navItem.classList.remove("active")
        })

        // Show target section and set active class
        if (targetSection) {
          targetSection.classList.remove("hidden")
          item.classList.add("active")
        }
      })
    })
  }
}

/**
 * Initialize dashboard tabs
 */
function initDashboardTabs() {
  const tabButtons = document.querySelectorAll(".dashboard-tab")
  const tabContents = document.querySelectorAll(".tab-content")

  if (tabButtons.length > 0 && tabContents.length > 0) {
    // Show first tab by default
    tabContents[0].classList.remove("hidden")
    tabButtons[0].classList.add("active")

    tabButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const targetId = button.getAttribute("data-tab")
        const targetContent = document.getElementById(targetId)

        // Hide all tab contents
        tabContents.forEach((content) => {
          content.classList.add("hidden")
        })

        // Remove active class from all tab buttons
        tabButtons.forEach((btn) => {
          btn.classList.remove("active")
        })

        // Show target content and set active class
        if (targetContent) {
          targetContent.classList.remove("hidden")
          button.classList.add("active")
        }
      })
    })
  }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
  const forms = document.querySelectorAll(".needs-validation")

  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      if (!form.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
      }

      form.classList.add("was-validated")
    })
  })
}

/**
 * Initialize image preview
 */
function initImagePreview() {
  const imageInputs = document.querySelectorAll(".image-upload")

  imageInputs.forEach((input) => {
    const previewId = input.getAttribute("data-preview")
    const preview = document.getElementById(previewId)

    if (preview) {
      input.addEventListener("change", () => {
        const file = input.files[0]

        if (file) {
          const reader = new FileReader()

          reader.onload = (e) => {
            preview.src = e.target.result
            preview.classList.remove("hidden")
          }

          reader.readAsDataURL(file)
        }
      })
    }
  })
}

/**
 * Initialize confirmation dialogs
 */
function initConfirmDialogs() {
  const confirmButtons = document.querySelectorAll("[data-confirm]")

  confirmButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      const message = button.getAttribute("data-confirm") || "Êtes-vous sûr de vouloir effectuer cette action?"

      if (!confirm(message)) {
        e.preventDefault()
      }
    })
  })
}

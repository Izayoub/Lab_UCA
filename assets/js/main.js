/**
 * Main JavaScript file for LIRE-RMD website
 */

document.addEventListener("DOMContentLoaded", () => {
  // Initialize animations on scroll
  initScrollAnimations()

  // Initialize mobile menu
  initMobileMenu()

  // Initialize dropdown menus
  initDropdowns()

  // Initialize smooth scrolling
  initSmoothScroll()

  // Initialize back to top button
  initBackToTop()
})

/**
 * Initialize animations on scroll
 */
function initScrollAnimations() {
  // Add fade-in animation to elements when they come into view
  const fadeElements = document.querySelectorAll(".fade-in")

  if (fadeElements.length > 0) {
    const fadeInObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible")
            fadeInObserver.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.1 },
    )

    fadeElements.forEach((element) => {
      fadeInObserver.observe(element)
    })
  }
}

/**
 * Initialize mobile menu
 */
function initMobileMenu() {
  const mobileMenuButton = document.getElementById("mobile-menu-button")
  const mobileMenu = document.getElementById("mobile-menu")

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden")
    })

    // Close mobile menu when clicking outside
    document.addEventListener("click", (e) => {
      if (
        !mobileMenuButton.contains(e.target) &&
        !mobileMenu.contains(e.target) &&
        !mobileMenu.classList.contains("hidden")
      ) {
        mobileMenu.classList.add("hidden")
      }
    })
  }
}

/**
 * Initialize dropdown menus
 */
function initDropdowns() {
  const dropdownButtons = document.querySelectorAll("[data-dropdown]")

  dropdownButtons.forEach((button) => {
    const targetId = button.getAttribute("data-dropdown")
    const dropdown = document.getElementById(targetId)

    if (dropdown) {
      button.addEventListener("click", (e) => {
        e.stopPropagation()
        dropdown.classList.toggle("active")
      })

      // Close dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
          dropdown.classList.remove("active")
        }
      })
    }
  })

  // User menu dropdown
  const userMenuButton = document.getElementById("user-menu-button")
  const userDropdown = document.querySelector(".dropdown-menu")

  if (userMenuButton && userDropdown) {
    userMenuButton.addEventListener("click", (e) => {
      e.stopPropagation()
      userDropdown.classList.toggle("active")
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
        userDropdown.classList.remove("active")
      }
    })
  }
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))

      if (target) {
        // Account for fixed header
        const headerOffset = 80
        const elementPosition = target.getBoundingClientRect().top
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset

        window.scrollTo({
          top: offsetPosition,
          behavior: "smooth",
        })
      }
    })
  })
}

/**
 * Initialize back to top button
 */
function initBackToTop() {
  const backToTopButton = document.getElementById("back-to-top")

  if (backToTopButton) {
    // Show/hide button based on scroll position
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.remove("opacity-0", "invisible")
        backToTopButton.classList.add("opacity-100", "visible")
      } else {
        backToTopButton.classList.remove("opacity-100", "visible")
        backToTopButton.classList.add("opacity-0", "invisible")
      }
    })

    // Scroll to top when clicked
    backToTopButton.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      })
    })
  }
}

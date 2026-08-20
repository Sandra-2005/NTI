document.addEventListener("DOMContentLoaded", function () {
  const menuBtn = document.getElementById("menuBtn");
  const navLinks = document.getElementById("navLinks");
  const slides = document.querySelectorAll(".hero-slide");
  const nextBtn = document.getElementById("nextSlide");
  const prevBtn = document.getElementById("prevSlide");
  const dotsContainer = document.getElementById("sliderDots");

  if (slides.length > 0 && nextBtn && prevBtn && dotsContainer) {
    let currentSlide = 0;

    slides.forEach(function (_, index) {
      const dot = document.createElement("button");

      dot.classList.add("slider-dot");

      if (index === 0) {
        dot.classList.add("active");
      }

      dot.addEventListener("click", function () {
        currentSlide = index;
        showSlide(currentSlide);
      });

      dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll(".slider-dot");

    function showSlide(index) {
      slides.forEach(function (slide) {
        slide.classList.remove("active");
      });

      dots.forEach(function (dot) {
        dot.classList.remove("active");
      });

      slides[index].classList.add("active");
      dots[index].classList.add("active");
    }

    nextBtn.addEventListener("click", function () {
      currentSlide++;

      if (currentSlide >= slides.length) {
        currentSlide = 0;
      }

      showSlide(currentSlide);
    });

    prevBtn.addEventListener("click", function () {
      currentSlide--;

      if (currentSlide < 0) {
        currentSlide = slides.length - 1;
      }

      showSlide(currentSlide);
    });

    setInterval(function () {
      currentSlide++;

      if (currentSlide >= slides.length) {
        currentSlide = 0;
      }

      showSlide(currentSlide);
    }, 6000);
  }

  const categoryButtons = document.querySelectorAll(".category-btn");
  const products = document.querySelectorAll(".product-card");

  if (categoryButtons.length > 0 && products.length > 0) {
    categoryButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        categoryButtons.forEach(function (item) {
          item.classList.remove("active");
        });

        button.classList.add("active");

        const category = button.dataset.category;

        products.forEach(function (product) {
          const productCategory = product.dataset.category;

          if (category === "all" || productCategory === category) {
            product.style.display = "block";
          } else {
            product.style.display = "none";
          }
        });
      });
    });
  }

  const workTabs = document.querySelectorAll(".work-tab");
  const workCards = document.querySelectorAll(".work-card");

  if (workTabs.length > 0 && workCards.length > 0) {
    workTabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        workTabs.forEach(function (item) {
          item.classList.remove("active");
        });

        tab.classList.add("active");

        const selectedWork = tab.dataset.work;

        workCards.forEach(function (card) {
          const cardWork = card.dataset.work;

          if (selectedWork === "all" || cardWork === selectedWork) {
            card.classList.remove("hidden");
          } else {
            card.classList.add("hidden");
          }
        });
      });
    });
  }

  const contactForm = document.getElementById("contactForm");

  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const message = document.getElementById("message").value.trim();

      if (name === "" || email === "" || message === "") {
        alert("Please fill in all fields.");
        return;
      }

      alert("Thank you, " + name + "! Your message has been sent.");

      contactForm.reset();
    });
  }
});

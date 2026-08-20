const CART_KEY = "heavenly_cart";

function parsePrice(text) {
  return parseFloat(String(text).replace(/[^0-9.]/g, "")) || 0;
}

function goToCheckout(product) {
  localStorage.setItem(CART_KEY, JSON.stringify([product]));
  window.location.href = "check_out.php";
}

document.addEventListener("DOMContentLoaded", function () {
  const productsGrid = document.getElementById("productsGrid");

  if (!productsGrid) {
    return;
  }

  productsGrid.addEventListener("click", function (event) {
    const button = event.target.closest(".product-bottom button");

    if (!button) {
      return;
    }

    event.preventDefault();

    const card = button.closest(".product-card");

    if (!card) {
      return;
    }
    const name = card.querySelector("h3").textContent.trim();
    const imageSrc = card
      .querySelector(".product-image img")
      .getAttribute("src");
    const priceText = card
      .querySelector(".product-bottom strong")
      .textContent.trim();
    const price = parsePrice(priceText);

    if (!window.IS_LOGGED_IN) {
      window.location.href = "login.php";
      return;
    }

    goToCheckout({
      id: name.toLowerCase().replace(/\s+/g, "-"),
      name: name,
      image: imageSrc,
      price: price,
      quantity: 1,
    });
  });
});

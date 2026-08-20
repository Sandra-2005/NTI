const CART_KEY = "heavenly_cart";
const WALLET_KEY = "heavenly_wallet_balance";

function getCart() {
  try {
    return JSON.parse(localStorage.getItem(CART_KEY)) || [];
  } catch (e) {
    return [];
  }
}

function getWalletBalance() {
  if (typeof window.SERVER_WALLET_BALANCE === "number") {
    return window.SERVER_WALLET_BALANCE;
  }

  return Number(localStorage.getItem(WALLET_KEY)) || 0;
}

function setWalletBalance(value) {
  if (typeof window.SERVER_WALLET_BALANCE === "number") {
    window.SERVER_WALLET_BALANCE = value;
  }

  localStorage.setItem(WALLET_KEY, value);
}

function formatMoney(amount) {
  return Number(amount || 0).toFixed(2) + " EGP";
}

function getSubtotal(cart) {
  return cart.reduce(function (sum, item) {
    return sum + (Number(item.price) || 0) * (Number(item.quantity) || 1);
  }, 0);
}

document.addEventListener("DOMContentLoaded", function () {
  const fields = {
    email: document.getElementById("email"),
    phone: document.getElementById("phone"),
    firstName: document.getElementById("firstName"),
  };

  const requiredMessages = {
    email: "Email is required",
    firstName: "Name is required",
    phone: "Phone is required",
  };

  const cartItemsEl = document.getElementById("cartItems");
  const subtotalValueEl = document.getElementById("subtotalValue");
  const totalValueEl = document.getElementById("totalValue");
  const remainingBalanceValueEl = document.getElementById(
    "remainingBalanceValue",
  );
  const placeOrderBtn = document.getElementById("placeOrderBtn");
  const walletBalanceDisplay = document.getElementById("walletBalanceDisplay");
  const walletNote = document.getElementById("walletNote");
  const successModal = document.getElementById("successModal");
  const modalOrderId = document.getElementById("modalOrderId");
  const modalTotal = document.getElementById("modalTotal");

  if (!cartItemsEl || !subtotalValueEl || !totalValueEl || !placeOrderBtn) {
    return;
  }

  function renderCart() {
    const cart = getCart();
    cartItemsEl.innerHTML = "";

    if (cart.length === 0) {
      cartItemsEl.innerHTML =
        '<p class="cart-empty">Your cart is empty. <a href="products.php">Browse products</a></p>';
    }

    cart.forEach(function (item) {
      const row = document.createElement("div");
      row.className = "cart-item";

      row.innerHTML =
        '<div class="cart-item-thumb">' +
        '<img src="' +
        encodeURI(item.image || "") +
        '" alt="' +
        (item.name || "Product") +
        '">' +
        "</div>" +
        '<div class="cart-item-info">' +
        '<p class="cart-item-name">' +
        (item.name || "Product") +
        "</p>" +
        '<p class="cart-item-qty">Qty: ' +
        (item.quantity || 1) +
        "</p>" +
        '<p class="cart-item-price">' +
        formatMoney((Number(item.price) || 0) * (Number(item.quantity) || 1)) +
        "</p>" +
        "</div>";

      cartItemsEl.appendChild(row);
    });

    const subtotal = getSubtotal(cart);
    const total = subtotal;

    subtotalValueEl.textContent = formatMoney(subtotal);
    totalValueEl.textContent = formatMoney(total);

    return total;
  }

  function renderWallet(total) {
    if (!walletBalanceDisplay || !remainingBalanceValueEl) {
      return;
    }

    const balance = getWalletBalance();
    const remaining = balance - total;

    walletBalanceDisplay.textContent = formatMoney(balance);
    remainingBalanceValueEl.textContent = formatMoney(remaining);

    if (window.IS_LOGGED_IN === false) {
      remainingBalanceValueEl.classList.remove("insufficient");
      if (walletNote) {
        walletNote.textContent = "Please log in to complete your order.";
      }
      placeOrderBtn.disabled = getCart().length === 0;
      return;
    }

    if (remaining < 0) {
      remainingBalanceValueEl.classList.add("insufficient");
      if (walletNote) {
        walletNote.textContent =
          "Insufficient wallet balance. Please recharge your wallet to continue.";
      }
      placeOrderBtn.disabled = true;
    } else {
      remainingBalanceValueEl.classList.remove("insufficient");
      if (walletNote) {
        walletNote.textContent = "";
      }
      placeOrderBtn.disabled = getCart().length === 0;
    }
  }

  function refreshPage() {
    const total = renderCart();
    renderWallet(total);
    return total;
  }

  function validateForm() {
    let isValid = true;

    Object.keys(fields).forEach(function (key) {
      const field = fields[key];
      const errorEl = document.getElementById(
        "err" + key.charAt(0).toUpperCase() + key.slice(1),
      );

      if (!field || !errorEl) {
        return;
      }

      const value = field.value.trim();

      if (!value) {
        field.classList.add("has-error");
        errorEl.textContent = requiredMessages[key];
        isValid = false;
      } else {
        field.classList.remove("has-error");
        errorEl.textContent = "";
      }
    });

    return isValid;
  }

  function placeOrder() {
    if (window.IS_LOGGED_IN === false) {
      window.location.href = "login.php";
      return;
    }

    if (!validateForm()) {
      return;
    }

    const cart = getCart();

    if (cart.length === 0) {
      return;
    }

    const total = getSubtotal(cart);
    const balance = getWalletBalance();

    if (balance < total) {
      refreshPage();
      return;
    }

    placeOrderBtn.disabled = true;
    placeOrderBtn.textContent = "Processing...";

    fetch("check_out.php?action=place_order", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: fields.email.value.trim(),
        phone: fields.phone.value.trim(),
        firstName: fields.firstName.value.trim(),
        cart: cart,
      }),
    })
      .then(function (response) {
        return response.json().then(function (data) {
          return { ok: response.ok, data: data };
        });
      })
      .then(function (result) {
        if (!result.ok || !result.data.success) {
          if (walletNote) {
            walletNote.textContent =
              result.data.message || "Could not place your order.";
          }
          refreshPage();
          return;
        }

        localStorage.removeItem(CART_KEY);

        if (typeof window.SERVER_WALLET_BALANCE === "number") {
          window.SERVER_WALLET_BALANCE = Number(result.data.newBalance);
        }

        localStorage.setItem(WALLET_KEY, Number(result.data.newBalance));

        if (modalOrderId) {
          modalOrderId.textContent = result.data.orderNumber;
        }

        if (modalTotal) {
          modalTotal.textContent = formatMoney(result.data.total);
        }

        if (successModal) {
          successModal.classList.add("active");
        }

        refreshPage();
      })
      .catch(function () {
        if (walletNote) {
          walletNote.textContent = "Something went wrong. Please try again.";
        }
        refreshPage();
      })
      .finally(function () {
        placeOrderBtn.textContent = "Place Order";
      });
  }

  refreshPage();
  placeOrderBtn.addEventListener("click", placeOrder);
});

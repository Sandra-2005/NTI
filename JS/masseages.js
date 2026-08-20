const searchInput = document.getElementById("searchInput");
const clearBtn = document.getElementById("clearSearchBtn");
const modal = document.getElementById("messageModal");
const messageRows = document.querySelectorAll(".message-row");
const messagesList = document.getElementById("messagesList");

const modalName = document.getElementById("modalName");
const modalPhone = document.getElementById("modalPhone");
const modalText = document.getElementById("modalText");
const modalCallLink = document.getElementById("modalCallLink");
const closeModalBtn = document.getElementById("closeModalBtn");
const cancelModalBtn = document.getElementById("cancelModalBtn");

messageRows.forEach((row) => {
  row.addEventListener("click", () => {
    const name = row.getAttribute("data-name") || "-";
    const phone = row.getAttribute("data-phone") || "-";
    const text = row.getAttribute("data-text") || "";

    if (modalName) modalName.textContent = name;
    if (modalPhone) modalPhone.textContent = phone;
    if (modalText) modalText.textContent = text;
    if (modalCallLink) {
      let cleanPhone = phone.replace(/[^0-9]/g, "");
      if (cleanPhone.startsWith("01")) {
        cleanPhone = "2" + cleanPhone;
      }
      modalCallLink.href = cleanPhone ? `https://wa.me/${cleanPhone}` : "#";
    }

    if (modal) modal.classList.add("active");
  });
});

function closeModal() {
  if (modal) modal.classList.remove("active");
}

if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
if (cancelModalBtn) cancelModalBtn.addEventListener("click", closeModal);
if (modal) {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });
}

if (searchInput) {
  searchInput.addEventListener("input", (e) => {
    const q = e.target.value.toLowerCase().trim();
    if (clearBtn) {
      clearBtn.style.display = q ? "block" : "none";
    }

    let visibleCount = 0;
    messageRows.forEach((row) => {
      const name = (row.getAttribute("data-name") || "").toLowerCase();
      const phone = (row.getAttribute("data-phone") || "").toLowerCase();
      const text = (row.getAttribute("data-text") || "").toLowerCase();

      if (name.includes(q) || phone.includes(q) || text.includes(q)) {
        row.style.display = "";
        visibleCount++;
      } else {
        row.style.display = "none";
      }
    });

    let emptyState = document.getElementById("jsEmptyState");
    if (visibleCount === 0 && messageRows.length > 0) {
      if (!emptyState) {
        emptyState = document.createElement("div");
        emptyState.id = "jsEmptyState";
        emptyState.className = "empty-state";
        emptyState.innerHTML =
          '<i class="fa-solid fa-magnifying-glass"></i><h4>No matching messages found</h4><p>Try searching with a different name, phone number, or keyword.</p>';
        if (messagesList) messagesList.appendChild(emptyState);
      }
    } else if (emptyState) {
      emptyState.remove();
    }
  });
}

if (clearBtn) {
  clearBtn.addEventListener("click", () => {
    if (searchInput) searchInput.value = "";
    clearBtn.style.display = "none";
    messageRows.forEach((row) => {
      row.style.display = "";
    });
    const emptyState = document.getElementById("jsEmptyState");
    if (emptyState) emptyState.remove();
  });
}

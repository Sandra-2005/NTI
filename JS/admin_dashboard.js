const lastLoginElement = document.getElementById("lastLogin");
const previousLogin = localStorage.getItem("heavenly_admin_last_login");

if (previousLogin) {
  lastLoginElement.textContent = previousLogin;
} else {
  lastLoginElement.textContent = "First Login";
}

const now = new Date();

const currentLogin = now.toLocaleString("EG", {
  year: "numeric",
  month: "long",
  day: "numeric",
  hour: "2-digit",
  minute: "2-digit",
});

localStorage.setItem("heavenly_admin_last_login", currentLogin);

const amountInput = document.getElementById("codeAmount");
const codeForm = document.getElementById("codeForm");
const generatedCodeField = document.getElementById("generatedCodeField");

function createCode() {
  const characters = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
  let code = "";

  for (let i = 0; i < 12; i++) {
    code += characters[Math.floor(Math.random() * characters.length)];
  }

  return code.match(/.{1,4}/g).join("-");
}

codeForm.addEventListener("submit", function (event) {
  const amount = Number(amountInput.value);

  if (!amount || amount <= 0) {
    event.preventDefault();

    amountInput.focus();
    amountInput.style.border = "1px solid #ED417A";

    setTimeout(() => {
      amountInput.style.border = "";
    }, 1200);

    return;
  }

  generatedCodeField.value = createCode();
});

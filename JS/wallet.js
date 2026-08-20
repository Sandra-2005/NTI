const rechargeCode = document.getElementById("rechargeCode");
rechargeCode.addEventListener("input", function () {
  const cleaned = this.value
    .toUpperCase()
    .replace(/[^A-Z0-9]/g, "")
    .slice(0, 12);

  this.value = cleaned.match(/.{1,4}/g)?.join("-") ?? cleaned;
});

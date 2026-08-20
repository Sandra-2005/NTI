const fileInput = document.getElementById("productImageFile");
const uploadBox = document.getElementById("fileUploadBox");
const uploadText = document.getElementById("uploadText");
const previewWrapper = document.getElementById("previewWrapper");
const imagePreview = document.getElementById("imagePreview");

if (uploadBox && fileInput) {
  uploadBox.addEventListener("click", () => fileInput.click());

  fileInput.addEventListener("change", (e) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.onload = function (event) {
        if (imagePreview) imagePreview.src = event.target.result;
        if (previewWrapper) previewWrapper.style.display = "block";
        if (uploadText) uploadText.textContent = `Selected: ${file.name}`;
      };
      reader.readAsDataURL(file);
    }
  });

  uploadBox.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "#b83a67";
  });

  uploadBox.addEventListener("dragleave", () => {
    uploadBox.style.borderColor = "#e8c2cf";
  });

  uploadBox.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = "#e8c2cf";
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      fileInput.files = e.dataTransfer.files;
      const file = e.dataTransfer.files[0];
      const reader = new FileReader();
      reader.onload = function (event) {
        if (imagePreview) imagePreview.src = event.target.result;
        if (previewWrapper) previewWrapper.style.display = "block";
        if (uploadText) uploadText.textContent = `Selected: ${file.name}`;
      };
      reader.readAsDataURL(file);
    }
  });
}

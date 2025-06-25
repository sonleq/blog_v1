document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get("error");

  if (error === "1") {
    showPopupError("Invalid username or password.");
  }

  const form = document.getElementById("loginForm");

  form.addEventListener("submit", function (e) {
    const username = form.username.value.trim();
    const password = form.password.value.trim();

    clearErrors();
    let hasError = false;

    if (username === "") {
      showFieldError(form.username, "Username is required.");
      hasError = true;
    }

    if (password === "") {
      showFieldError(form.password, "Password is required.");
      hasError = true;
    }

    if (hasError) {
      e.preventDefault();
      form.classList.add("shake");
      setTimeout(() => form.classList.remove("shake"), 400);
    }
  });

  function showFieldError(input, message) {
    input.classList.add("input-error");

    const error = document.createElement("div");
    error.className = "error-message";
    error.innerText = message;

    input.parentElement.appendChild(error);
  }

  function clearErrors() {
    document.querySelectorAll(".input-error").forEach(el => el.classList.remove("input-error"));
    document.querySelectorAll(".error-message").forEach(el => el.remove());
  }

  function showPopupError(message) {
    const popup = document.createElement("div");
    popup.id = "error-popup";
    popup.textContent = message;

    document.body.appendChild(popup);

    setTimeout(() => {
      popup.remove();
    }, 4000);
  }
});

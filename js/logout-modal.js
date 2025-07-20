document.addEventListener("DOMContentLoaded", function () {
  // ðŸ”¹ Create a <style> tag and inject CSS for the logout modal
  const style = document.createElement("style");
  style.innerHTML = `
    /* ðŸ”’ Base modal styles (hidden by default) */
    #logoutModal {
      position: fixed;
      top: 15%;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(30, 0, 0, 0.85);
      color: #ff6666;
      padding: 30px 40px;
      border-radius: 16px;
      font-size: 1.4em;
      border: 3px solid #ff1e1e;
      box-shadow: 0 0 20px #ff1e1e;
      z-index: 9999;
      text-shadow: 0 0 3px #ff1e1e;
      text-align: center;
      display: none; /* Hidden by default */
      align-items: center;
      justify-content: center;
      gap: 16px;
      min-width: 350px;
      max-width: 600px;
      flex-direction: column;
    }

    /* ðŸ‘ï¸ Show modal when 'show' class is added */
    #logoutModal.show {
      display: flex;
    }

    /* ðŸ§± Styles for both buttons */
    #logoutModal button {
      font-size: 1em;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin: 0 10px;
    }

    /* ðŸ”´ Confirm button (Logout) styling */
    #confirmLogout {
      background-color: #ff1e1e;
      color: white;
    }

    /* âš« Cancel button styling */
    #cancelLogout {
      background-color: #222;
      color: #ccc;
    }

    /* ðŸ–±ï¸ Hover effect for both buttons */
    #confirmLogout:hover,
    #cancelLogout:hover {
      opacity: 0.9;
    }
	

    /* ðŸ“¦ Button group container layout */
    #logoutModal .btn-group {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }
  `;
  document.head.appendChild(style); // ðŸ“Œ Add the style block to the <head>

  // ðŸ§© Insert the modal HTML into the end of <body>
  const modalHTML = `
    <div id="logoutModal">
      <p>Are you sure you want to logout?</p>
      <div class="btn-group">
        <button id="confirmLogout">Yes, Logout</button>
        <button id="cancelLogout">Cancel</button>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // ðŸ” Get references to DOM elements
  const logoutBtn = document.getElementById("logoutBtn");
  const logoutModal = document.getElementById("logoutModal");
  const confirmLogout = document.getElementById("confirmLogout");
  const cancelLogout = document.getElementById("cancelLogout");

  // ðŸ“¢ Show the modal when logout button is clicked
  if (logoutBtn && logoutModal) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault(); // ðŸ›‘ Prevent the default link action
      logoutModal.classList.add("show"); // ðŸ‘ï¸ Show the modal
    });
  }

  // âœ… If user confirms logout, redirect to logout.php
  confirmLogout.addEventListener("click", function () {
    window.location.href = "logout.php";
  });

  // âŒ If user cancels, hide the modal
  cancelLogout.addEventListener("click", function () {
    logoutModal.classList.remove("show");
  });

  // ðŸ–±ï¸ Hide modal if user clicks outside of it
  window.addEventListener("click", function (e) {
    if (e.target === logoutModal) {
      logoutModal.classList.remove("show");
    }
  });
});

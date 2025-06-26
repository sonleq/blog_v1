/* ==================================== Dashboard Stats Update Logic ============================== */ 
/* ==================================== Dashboard Stats Update Logic ============================== */ 
/* ==================================== Dashboard Stats Update Logic ============================== */ 
 // Fetch latest stats every 30 seconds
        function updateDashboardStats() {
            fetch('includes/dashboard-update.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('num-blogs').textContent = Number(data.blogs).toLocaleString();
                    document.getElementById('num-comments').textContent = Number(data.comments).toLocaleString();
                    document.getElementById('num-subscribers').textContent = Number(data.subscribers).toLocaleString();
                })
                .catch(error => console.error("Error loading stats:", error));
        }

        // Run on page load + every 30 seconds
        updateDashboardStats();
        setInterval(updateDashboardStats, 10000);

 
 /* ================================================================== Timeout Warning Logic ================================ */
  /* ================================================================== Timeout Warning Logic ================================ */
   /* ================================================================== Timeout Warning Logic ================================ */
 document.addEventListener("DOMContentLoaded", () => {
  // === CONFIGURATION ===
  const idleLimit = 800000;             //  seconds of no activity before showing the warning
  const warningCountdown = 10000;       // second countdown warning
  const logoutDelay = idleLimit + warningCountdown; //  seconds total before logout

  // === STATE ===
  let warningTimer, logoutTimer, countdownInterval;
  let secondsLeft = warningCountdown / 1000;

  // === RESET ALL TIMERS ON USER ACTIVITY ===
  const resetInactivityTimers = () => {
    clearTimeout(warningTimer);
    clearTimeout(logoutTimer);
    clearInterval(countdownInterval);
    removeWarningPopup();

    warningTimer = setTimeout(showInactivityWarning, idleLimit);

    logoutTimer = setTimeout(() => {
      // Call logout.php via POST, then redirect
      fetch("http://localhost/blog/logout.php", { method: "POST" })
        .then(() => {
          window.location.href = "http://localhost/blog/login.html";
        })
        .catch(() => {
          window.location.href = "http://localhost/blog/login.html";
        });
    }, logoutDelay);
  };

  // === DISPLAY WARNING POPUP ===
  const showInactivityWarning = () => {
    if (document.getElementById("inactivity-warning")) return;

    const popup = document.createElement("div");
	  popup.id = "inactivity-warning";
	  popup.style = `
	  position: fixed;
	  top: 15%;
	  left: 50%;
	  transform: translateX(-50%);
	  background: rgba(30, 0, 0, 0.85);
	  color: #ff6666;
	  padding: 30px 40px;            /* bigger padding */
	  border-radius: 16px;
	  font-size: 1.4em;             /* bigger font */
	  border: 3px solid #ff1e1e;
	  box-shadow: 0 0 20px #ff1e1e;
	  z-index: 9999;
	  text-shadow: 0 0 3px #ff1e1e;
	  text-align: center;
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  gap: 16px;
	  min-width: 350px;             /* minimum width */
	  max-width: 600px;             /* maximum width */
	`;


    const minutes = Math.floor(secondsLeft / 60);
const seconds = (secondsLeft % 60).toString().padStart(2, '0');

popup.innerHTML = `
  <span>You will be logged out in <strong><span id="countdown">${minutes}min ${seconds}sec</span></strong> due to inactivity.</span>
      <button id="dismissWarning" style="
        background: #ff1e1e;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        cursor: pointer;
        font-weight: bold;
        box-shadow: 0 0 6px #ff1e1e;
      ">Dismiss</button>
    `;

    document.body.appendChild(popup);

    document.getElementById("dismissWarning").addEventListener("click", () => {
      removeWarningPopup();
      resetInactivityTimers();
    });

    countdownInterval = setInterval(() => {
      secondsLeft--;
      const countdownEl = document.getElementById("countdown");
      if (countdownEl) {
        const minutes = Math.floor(secondsLeft / 60);
        const seconds = (secondsLeft % 60).toString().padStart(2, '0');
        countdownEl.textContent = `${minutes}min ${seconds}sec`;
      }
      if (secondsLeft <= 0) clearInterval(countdownInterval);
    }, 1000);
  };

  // === REMOVE WARNING POPUP ===
  const removeWarningPopup = () => {
    const popup = document.getElementById("inactivity-warning");
    if (popup) popup.remove();
    secondsLeft = warningCountdown / 1000;
  };

  // === USER ACTIVITY EVENTS ===
  ["click", "mousemove", "keypress", "scroll"].forEach(event =>
    document.addEventListener(event, resetInactivityTimers)
  );

  // === INIT ===
  resetInactivityTimers();
});
 

document.addEventListener("DOMContentLoaded", () => {
 /* const timeout = 30 * 60 * 1000; // 30 minutes
  const warningTime = 1 * 60 * 1000; // warn at 29 minutes
  const logoutTime = timeout + 5 * 1000; // extra buffer*/
  
 const timeout = 1 * 20 * 1000;       // 1 minute total timeout
const warningTime = 15 * 1000;       // warn at 30 seconds
const logoutTime = timeout + 5 * 1000;  // logout at 1:05 min

  let warningTimer, logoutTimer;
  let countdownInterval;
  let timeLeft = 10; // seconds for countdown

  function resetTimers() {
    clearTimeout(warningTimer);
    clearTimeout(logoutTimer);
    clearInterval(countdownInterval);
    removeWarningPopup();

    warningTimer = setTimeout(showWarningPopup, timeout - warningTime);
    logoutTimer = setTimeout(() => {
      window.location.href = "http://localhost/blog/logout.php";
    }, logoutTime);
  }

  function showWarningPopup() {
    if (document.getElementById("timeout-warning")) return;

    const popup = document.createElement("div");
    popup.id = "timeout-warning";
    popup.style.position = "fixed";
    popup.style.top = "15%";
    popup.style.left = "50%";
    popup.style.transform = "translateX(-50%)";
    popup.style.background = "rgba(30, 0, 0, 0.85)";
    popup.style.color = "#ff6666";
    popup.style.padding = "14px 24px";
    popup.style.borderRadius = "12px";
    popup.style.fontSize = "1em";
    popup.style.border = "2px solid #ff1e1e";
    popup.style.boxShadow = "0 0 12px #ff1e1e";
    popup.style.zIndex = "9999";
    popup.style.textShadow = "0 0 2px #ff1e1e";
    popup.style.textAlign = "center";
    popup.style.display = "flex";
    popup.style.alignItems = "center";
    popup.style.justifyContent = "center";
    popup.style.gap = "12px";

    popup.innerHTML = `
      <span>You will be logged out in <strong><span id="countdown">10</span></strong> seconds due to inactivity.</span>
      <button id="dismissTimeoutWarning" style="
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

    document.getElementById("dismissTimeoutWarning").addEventListener("click", () => {
      removeWarningPopup();
      resetTimers(); // reset timers on dismiss as well
    });

    countdownInterval = setInterval(() => {
      timeLeft--;
      document.getElementById("countdown").textContent = timeLeft;
      if (timeLeft <= 0) clearInterval(countdownInterval);
    }, 1000);
  }

  function removeWarningPopup() {
    const popup = document.getElementById("timeout-warning");
    if (popup) popup.remove();
    timeLeft = 10;
  }

  // Activity resets the timers
  ["click", "mousemove", "keypress", "scroll"].forEach(event => {
    document.addEventListener(event, resetTimers);
  });

  resetTimers(); // Initialize timers
});
 

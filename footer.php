<footer class="s-footer">

  <div class="s-footer__main">

    <div class="row">

      <div class="column large-3 medium-6 tab-12 s-footer__info">

        <h5>About Our Site</h5>
        <p>
          Calvin is a personal blog sharing stories, insights, and ideas across tech, design, and everyday life.
        </p>

      </div> <!-- end s-footer__info -->

      <div class="column large-2 medium-3 tab-6 s-footer__site-links">

        <h5>Site Links</h5>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>

      </div> <!-- end s-footer__site-links -->

      <div class="column large-2 medium-3 tab-6 s-footer__social-links">

        <h5>Follow Us</h5>
        <ul>
          <li><a href="https://www.instagram.com/son_qle/" target="_blank" rel="noopener noreferrer" >Instagram</a></li>
          <li><a href="https://x.com/sonquangle" target="_blank" rel="noopener noreferrer" >X</a></li>
          <li><a href="https://www.facebook.com/slqsonle" target="_blank" rel="noopener noreferrer" >Facebook</a></li>
          <li><a href="#" target="_blank" rel="noopener noreferrer" >Youtube</a></li>
        </ul>

      </div> <!-- end s-footer__social links -->

      <div class="column large-3 medium-6 tab-12 s-footer__subscribe">

        <h5>Sign Up for Newsletter</h5>
        <p>Signup to get updates on articles, interviews and events.</p>

        <div class="subscribe-form">

          <form action="subscription/subscribe.php" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>
            <input type="email" name="email" class="email" id="mce-EMAIL" placeholder="Your Email Address" required>
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups -->
            <div style="position: absolute; left: -5000px;" aria-hidden="true">
              <input type="text" name="b_xxxxxx_xxxxxx" tabindex="-1" value="">
            </div>
            <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
          </form>

          <div id="subscribe-message" style="margin-top: 1em; color: #333;"></div>

        </div>

      </div> <!-- end s-footer__subscribe -->

    </div> <!-- end row -->

  </div> <!-- end s-footer__main -->

  <div class="s-footer__bottom">
    <div class="row">
      <div class="column">
        <div class="ss-copyright">
          <span>&copy; The <?php echo htmlspecialchars(date('Y')); ?></span>
          <span>Design by <a href="https://www.styleshout.com/">StyleShout</a></span>
          <span><a href="privacy.php">Privacy Policy</a></span>
        </div> <!-- end ss-copyright -->
      </div>
    </div>

    <div class="ss-go-top">
      <a class="smoothscroll" title="Back to Top" href="#top">
        <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
          <path d="M7.5 1.5l.354-.354L7.5.793l-.354.353.354.354zm-.354.354l4 4 .708-.708-4-4-.708.708zm0-.708l-4 4 .708.708 4-4-.708-.708zM7 1.5V14h1V1.5H7z" fill="currentColor" />
        </svg>
      </a>
    </div> <!-- end ss-go-top -->
  </div> <!-- end s-footer__bottom -->

</footer>

<script>
  // AJAX subscription form submission
  document.getElementById('mc-embedded-subscribe-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const emailInput = form.querySelector('#mce-EMAIL');
    const messageDiv = document.getElementById('subscribe-message');
    messageDiv.textContent = '';
    const submitBtn = form.querySelector('input[type="submit"]');

    // Disable button while processing
    submitBtn.disabled = true;
    submitBtn.value = "Subscribing...";

    const formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.text())
      .then(data => {
        messageDiv.innerHTML = data;

        // If success message detected, clear input and optionally disable form
        if (data.includes("✅")) {
          emailInput.value = '';
          // submitBtn.disabled = true; // Optional: disable submit after success
        } else {
          // Re-enable button if error
          submitBtn.disabled = false;
          submitBtn.value = "Subscribe";
        }
      })
      .catch(() => {
        messageDiv.textContent = "❌ An error occurred. Please try again.";
        submitBtn.disabled = false;
        submitBtn.value = "Subscribe";
      });
  });
</script>

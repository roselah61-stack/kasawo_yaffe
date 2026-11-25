// Shared header/footer for the Kasawo Yaffe mockup
(function () {
  const page = document.body.dataset.page || '';
  const links = [
    ['index.html', 'Home', 'home'],
    ['story.html', 'Our Story', 'story'],
    ['explore.html', 'Explore', 'explore'],
    ['marketplace.html', 'Marketplace', 'market'],
    ['community.html', 'Community & Advice', 'community'],
  ];

  const header = `
  <div class="mock-bar"><b>MOCKUP</b> · Photos are real, freely licensed photos from Buganda and Mukono District, standing in until we have photos of Kasawo. Text and listings are samples.</div>
  <header class="site-header">
    <div class="wrap nav">
      <a href="index.html" class="logo"><span class="logo-mark">K</span><span>Kasawo <em>Yaffe</em></span></a>
      <ul class="nav-links" id="navLinks">
        ${links.map(([h, t, k]) => `<li><a href="${h}" class="${k === page ? 'active' : ''}">${t}</a></li>`).join('')}
      </ul>
      <div class="nav-cta">
        <a href="#" class="btn btn-outline btn-sm">Sign in</a>
        <a href="list-business.html" class="btn btn-primary btn-sm">List your business</a>
        <button class="menu-btn" aria-label="Menu" onclick="document.getElementById('navLinks').classList.toggle('open')">☰</button>
      </div>
    </div>
  </header>`;

  const footer = `
  <footer class="site-footer">
    <div class="wrap">
      <div class="foot-grid">
        <div>
          <a href="index.html" class="logo"><span class="logo-mark">K</span><span>Kasawo Yaffe</span></a>
          <p>Our home, our story, our people. The online home of Kasawo: its history, places to visit, and the businesses that keep it going.</p>
          <p style="font-size:.85rem">Kasawo, Mukono District · Central Uganda</p>
        </div>
        <div>
          <h4>Discover</h4>
          <ul><li><a href="story.html">Our Story</a></li><li><a href="explore.html">Places to visit</a></li><li><a href="explore.html#events">Events</a></li><li><a href="story.html#gallery">Photo gallery</a></li></ul>
        </div>
        <div>
          <h4>Business</h4>
          <ul><li><a href="marketplace.html">Marketplace</a></li><li><a href="list-business.html">List your business</a></li><li><a href="list-business.html#plans">Advertise</a></li><li><a href="community.html">Business advice</a></li></ul>
        </div>
        <div>
          <h4>Stay in touch</h4>
          <p style="font-size:.9rem">News, events and new businesses, once a month.</p>
          <div class="newsletter"><input placeholder="Your email or phone"><button class="btn btn-primary btn-sm">Join</button></div>
        </div>
      </div>
      <div class="foot-bottom">
        <span>© 2026 Kasawo Yaffe · Webale kujja (thank you for visiting)</span>
        <span><a href="CREDITS.md">Photo credits</a> · Privacy · Terms</span>
      </div>
    </div>
  </footer>`;

  document.body.insertAdjacentHTML('afterbegin', header);
  document.body.insertAdjacentHTML('beforeend', footer);

  // Filter chips toggle (visual only)
  document.querySelectorAll('.chips').forEach((group) => {
    group.addEventListener('click', (e) => {
      const chip = e.target.closest('.chip');
      if (!chip) return;
      group.querySelectorAll('.chip').forEach((c) => c.classList.remove('on'));
      chip.classList.add('on');
    });
  });
})();

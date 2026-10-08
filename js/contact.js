// CREATE: sends the public contact form to Supabase. No visitor account needed.
(function () {
  var form = document.getElementById('contactForm'), status = document.getElementById('status');
  if (!form) return;
  function say(t) { status.textContent = t; status.hidden = false; }
  form.addEventListener('submit', function (ev) {
    ev.preventDefault();
    if (/YOUR-/.test(CFG.url + CFG.key)) return say('The contact form is not connected yet. Please email me instead.');
    var f = new FormData(form);
    if (f.get('website')) return say('Message sent. Thank you, I will reply soon.'); // honeypot: bots fill this hidden field
    try { if (Date.now() - Number(localStorage.getItem('lastSent') || 0) < 30000) return say('Please wait 30 seconds before sending another message.'); } catch (e) {}
    var btn = form.querySelector('button[type=submit]'); btn.disabled = true;
    fetch(CFG.url + '/rest/v1/messages', {
      method: 'POST',
      headers: { apikey: CFG.key, Authorization: 'Bearer ' + CFG.key, 'Content-Type': 'application/json', Prefer: 'return=minimal' },
      body: JSON.stringify({
        name: f.get('name').trim(), email: f.get('email').trim(), company: (f.get('company') || '').trim(),
        inquiry_type: f.get('inquiry_type'), subject: f.get('subject').trim(), body: f.get('body').trim()
      })
    }).then(function (r) {
      if (!r.ok) throw new Error(r.status);
      form.reset(); say('Message sent. Thank you, I will reply soon.');
      try { localStorage.setItem('lastSent', Date.now()); } catch (e) {}
    }).catch(function () { say('Could not send your message. Please email me at angelotindog@gmail.com.'); })
      .then(function () { btn.disabled = false; });
  });
})();

// Admin inbox: log in with Supabase Auth, then READ, UPDATE and DELETE messages.
(function () {
  var token = null;
  try { token = sessionStorage.getItem('tok'); } catch (e) {}
  var $ = function (id) { return document.getElementById(id); };
  function el(tag, cls, text) { var n = document.createElement(tag); if (cls) n.className = cls; if (text != null) n.textContent = text; return n; }
  function err(t) { $('err').textContent = t; $('err').hidden = !t; }
  function api(path, method, body) {
    return fetch(CFG.url + '/rest/v1/' + path, {
      method: method || 'GET',
      headers: { apikey: CFG.key, Authorization: 'Bearer ' + token, 'Content-Type': 'application/json', Prefer: 'return=minimal' },
      body: body ? JSON.stringify(body) : undefined
    }).then(function (r) {
      if (r.status === 401) { logout(); throw new Error('expired'); }
      if (!r.ok) throw new Error(r.status);
      return method && method !== 'GET' ? null : r.json();
    });
  }
  function logout() {
    token = null; try { sessionStorage.removeItem('tok'); } catch (e) {}
    $('inbox').hidden = true; $('login').hidden = false;
  }
  function load() {
    $('login').hidden = true; $('inbox').hidden = false;
    api('messages?select=*&order=created_at.desc').then(render).catch(function (e) { if (e.message !== 'expired') $('count').textContent = 'Could not load messages.'; });
  }
  function render(rows) {
    var unread = rows.filter(function (m) { return !m.is_read; }).length;
    $('count').textContent = rows.length + ' messages, ' + unread + ' unread.';
    var list = $('list'); list.innerHTML = '';
    if (!rows.length) list.appendChild(el('p', 'muted', 'No messages yet. They appear here when visitors use the contact form.'));
    rows.forEach(function (m) { list.appendChild(card(m)); });
  }
  function btn(label, cls, fn) { var b = el('button', 'btn ' + (cls || ''), label); b.type = 'button'; b.addEventListener('click', fn); return b; }
  function card(m) {
    var a = el('article', 'msg' + (m.is_read ? '' : ' unread'));
    var h = el('h3', null, m.subject); if (!m.is_read) { h.appendChild(document.createTextNode(' ')); h.appendChild(el('span', 'badge', 'New')); }
    var meta = el('p', 'muted', m.name + (m.company ? ' (' + m.company + ')' : '') + ' · ' + m.inquiry_type + ' · ');
    var mail = el('a', null, m.email); mail.href = 'mailto:' + m.email; meta.appendChild(mail);
    meta.appendChild(document.createTextNode(' · ' + new Date(m.created_at).toLocaleString() + (m.updated_at ? ' · edited' : '')));
    var body = el('p', null, m.body); body.style.whiteSpace = 'pre-wrap';
    var row = el('div', 'row');
    row.appendChild(btn('Mark ' + (m.is_read ? 'unread' : 'read'), '', function () { api('messages?id=eq.' + m.id, 'PATCH', { is_read: !m.is_read }).then(load); }));
    row.appendChild(btn('Edit', '', function () { a.replaceWith(editor(m)); }));
    row.appendChild(btn('Delete', 'danger', function () { if (confirm('Delete this message?')) api('messages?id=eq.' + m.id, 'DELETE').then(load); }));
    [h, meta, body, row].forEach(function (n) { a.appendChild(n); });
    return a;
  }
  function editor(m) {
    var f = el('form', 'form msg');
    function field(label, key, area) {
      var l = el('label', null, label), i = el(area ? 'textarea' : 'input'); i.value = m[key]; i.required = true; if (area) i.rows = 5; i.name = key; l.appendChild(i); f.appendChild(l);
    }
    field('Name', 'name'); field('Subject', 'subject'); field('Message', 'body', true);
    var row = el('div', 'row'); var save = el('button', 'btn p', 'Save changes'); save.type = 'submit';
    row.appendChild(save); row.appendChild(btn('Cancel', '', load)); f.appendChild(row);
    f.addEventListener('submit', function (ev) {
      ev.preventDefault();
      api('messages?id=eq.' + m.id, 'PATCH', { name: f.name.value.trim(), subject: f.subject.value.trim(), body: f.body.value.trim(), updated_at: new Date().toISOString() }).then(load);
    });
    return f;
  }
  $('loginForm').addEventListener('submit', function (ev) {
    ev.preventDefault(); err('');
    if (/YOUR-/.test(CFG.url + CFG.key)) return err('Add your Supabase URL and key in js/config.js first.');
    var f = ev.target;
    fetch(CFG.url + '/auth/v1/token?grant_type=password', {
      method: 'POST', headers: { apikey: CFG.key, 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: f.email.value.trim(), password: f.password.value })
    }).then(function (r) { return r.json(); }).then(function (d) {
      if (!d.access_token) return err('Wrong email or password.');
      token = d.access_token; try { sessionStorage.setItem('tok', token); } catch (e) {}
      f.reset(); load();
    }).catch(function () { err('Could not reach Supabase. Check js/config.js.'); });
  });
  $('logout').addEventListener('click', logout);
  if (token) load();
})();

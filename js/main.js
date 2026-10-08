// Theme toggle: switches between light and dark mode.
document.getElementById('theme').addEventListener('click',function(){
  var r=document.documentElement,t=r.getAttribute('data-theme');
  var dark=t?t==='dark':window.matchMedia('(prefers-color-scheme: dark)').matches;
  r.setAttribute('data-theme',dark?'light':'dark');
});

// Show the contact form result (the server redirects back with ?sent=1 or ?error=...).
(function(){
  var s=document.getElementById('status');if(!s)return;
  var p=new URLSearchParams(location.search);
  if(p.get('sent')){s.textContent='Message sent. Thank you, I will reply soon.';s.hidden=false}
  else if(p.get('error')==='wait'){s.textContent='Please wait 30 seconds before sending another message.';s.hidden=false}
  else if(p.get('error')){s.textContent='Please fill in every field with a valid email.';s.hidden=false}
})();

// Copy email button for quick contact.
(function(){
  var b=document.getElementById('copy');if(!b)return;
  b.addEventListener('click',function(){
    var m='angelotindog@gmail.com';
    function done(){b.textContent='Copied';setTimeout(function(){b.textContent='Copy email'},1800)}
    if(navigator.clipboard){navigator.clipboard.writeText(m).then(done,function(){b.textContent=m})}else{b.textContent=m}
  });
})();

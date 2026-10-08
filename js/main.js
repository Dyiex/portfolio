// Theme toggle: switches between light and dark mode.
document.getElementById('theme').addEventListener('click',function(){
  var r=document.documentElement,t=r.getAttribute('data-theme');
  var dark=t?t==='dark':window.matchMedia('(prefers-color-scheme: dark)').matches;
  r.setAttribute('data-theme',dark?'light':'dark');
});

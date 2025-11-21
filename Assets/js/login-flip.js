// Login Page Flipbox control - Handles flip animation between login and password recovery
document.querySelectorAll('.login-content [data-toggle="flip"]').forEach(function(el){
  el.addEventListener('click', function(e){
    e.preventDefault();
    document.querySelector('.login-box').classList.toggle('flipped');
  });
});

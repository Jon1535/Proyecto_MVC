// Image Preview Script - Handles file input preview for image uploads
document.addEventListener('DOMContentLoaded', function() {
  const photoInput = document.querySelector('input[name="fotos[]"]');
  if (photoInput) {
    photoInput.addEventListener('change', function(e) {
      const miniaturasContainer = document.getElementById('miniaturasContainer');
      if (miniaturasContainer) {
        miniaturasContainer.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
          const reader = new FileReader();
          reader.onload = function(event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            img.className = 'img-thumbnail';
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.style.margin = '5px';
            miniaturasContainer.appendChild(img);
          };
          reader.readAsDataURL(file);
        });
      }
    });
  }
});

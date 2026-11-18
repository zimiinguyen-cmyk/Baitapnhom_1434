document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', event => {
      if (!window.confirm('Bạn chắc chắn muốn xóa vĩnh viễn hồ sơ này?')) event.preventDefault();
    });
  });
  const fileInput = document.querySelector('#avatar');
  const preview = document.querySelector('#avatar-preview');
  if (fileInput && preview) {
    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (!file) return;
      if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
        alert('Ảnh phải là JPG, PNG hoặc WEBP và không vượt quá 2 MB.');
        fileInput.value = '';
        return;
      }
      const url = URL.createObjectURL(file);
      preview.onload = () => URL.revokeObjectURL(url);
      preview.src = url;
    });
  }
  const form = document.querySelector('#student-form');
  if (form) form.addEventListener('submit', event => {
    if (!form.reportValidity()) event.preventDefault();
  });
});

document.querySelector('#language-form-select').addEventListener('change', (e) => {
    e.target.closest('form').submit();
});

document.querySelector('#language-form-select').addEventListener('change', (e) => {
    e.target.closest('form').submit();
});

if (document.querySelector('#favorite-button') !== null) {
    document.querySelector('#favorite-button').addEventListener('click', (e) => {
        let id = e.target.dataset.id;
        fetch(`/api/favorite/${id}`, {headers: {'Content-Type': 'application/json'}})
            .then((res) => res.json())
            .then((data) => {
                if (data.result) {
                    e.target.classList.toggle('favorite');
                }
            });
    });
}

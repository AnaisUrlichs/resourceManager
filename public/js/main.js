const authors = document.getElementById('authors');

if(authors) {
    //authors.addEventListener('click', (e) => {
        if(e.target.className === 'btn btn-danger delete-author') {
            if(confirm('Are You Sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch('/author/delete/${id}', {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}
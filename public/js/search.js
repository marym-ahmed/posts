document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const query = document.getElementById('query').value;

        fetch(`/search?query=${query}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(post => {
                        const postElement = document.createElement('div');
                        const postTitle = document.createElement('h2');
                        const postContent = document.createElement('p');

                        postTitle.textContent = post.title;
                        postContent.textContent = post.content;

                        postElement.appendChild(postTitle);
                        postElement.appendChild(postContent);
                        searchResults.appendChild(postElement);
                    });
                } else {
                    searchResults.innerHTML = '<p>No posts found.</p>';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = '<p>An error occurred while searching. Please try again.</p>';
            }
        });
    });
});

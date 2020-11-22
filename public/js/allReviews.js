const paginationDiv = document.querySelector('.review-pagination');
const numOfPages = paginationDiv.getAttribute('data-pages');


for (let i = 1; i <= numOfPages; i++) {

  const anchor = document.createElement('a');

  anchor.setAttribute('href', `/users/reviews?page=${i}`);
  anchor.textContent = i;

  paginationDiv.appendChild(anchor);

}
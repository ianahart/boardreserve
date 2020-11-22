const pagination = document.querySelector('.user-pagination');
const numberOfPages = pagination.getAttribute('data-total-users');





for(let i = 1; i <= numberOfPages; i++) {

  const anchor = document.createElement('a');

  anchor.setAttribute('href', `/users?page=${i}`);

  anchor.textContent = i;

  pagination.appendChild(anchor);
}








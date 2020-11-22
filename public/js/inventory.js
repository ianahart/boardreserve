const sortForm = document.querySelector('#sort-form');
const selectTag = document.querySelector('#sort-form select');

sortForm.addEventListener('submit', () => {

sortForm.setAttribute('action', `/snowboards/sort/?selected=${selectTag.value}`);
});

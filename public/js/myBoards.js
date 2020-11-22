const deleteButtons = document.querySelectorAll('.seller-delete');
const deleteModals = document.querySelectorAll('.snowboard-delete-modal');
const hideModalButtons = document.querySelectorAll('.cancel-modal');

const addClassName = (element, className) => {

  element.classList.add(className);
}

const removeClassName = (element, className) => {

  element.classList.remove(className);
}

const showDeleteModal = (e) => {
  deleteModals.forEach((deleteModal) => {

    if (!deleteModal.classList.contains('hidden')) {
      addClassName(deleteModal, 'hidden');
    }
  });
  const deleteModal = e.target.previousElementSibling.previousElementSibling;

  removeClassName(deleteModal, 'hidden');
}

const hideDeleteModal = (e) => {

  const deleteModal = e.target.parentElement.parentElement.parentElement;

  deleteModal.querySelector('.form-error').textContent = '';



    addClassName(deleteModal, 'hidden');


}


deleteButtons.forEach((deleteButton) => {

  deleteButton.addEventListener('click', showDeleteModal);
})

hideModalButtons.forEach((hideModalButton) => {

  hideModalButton.addEventListener('click', hideDeleteModal);
})


deleteModals.forEach((deleteModal)  => {
  const formError = deleteModal.querySelector('.form-error')
          .textContent
          .replace(/[\n\r]+|[\s]{2,}/g, ' ')
          .trim();


  if (formError.length > 0) {
    removeClassName(deleteModal, 'hidden');
  }
});




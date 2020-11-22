const uploadBox = document.querySelector('.upload-box');
const uploadInput = document.querySelector('#fileElem');
const uploadLabel = document.querySelector('#upload-label');
const uploadIcon = document.querySelector('.upload-icon');
const uploadCompleteIcon = document.querySelector('.upload-complete-icon');

const preventDefaults = (e) => {
  e.preventDefault();
  e.stopPropagation();
}


const highlightBox = () => {
  uploadBox.classList.add('highlight');
}

const unhighlightBox = () => {
  uploadBox.classList.remove('highlight');
}

const handleUpload = (e) => {
  uploadInput.files = e.dataTransfer.files;
  uploadLabel.textContent = 'Upload completed';
  uploadIcon.classList.add('hidden');
  uploadCompleteIcon.classList.remove('hidden');


}

['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
  uploadBox.addEventListener(eventName, preventDefaults, false);
});

['dragenter', 'dragover'].forEach((eventName) => {
  uploadBox.addEventListener(eventName, highlightBox);
});

['dragleave', 'drop'].forEach((eventName) => {
  uploadBox.addEventListener(eventName, unhighlightBox);
});



uploadBox.addEventListener('drop', handleUpload, false);
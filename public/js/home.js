

const addClassName = (element, className) => {
  element.classList.add(className);
}

const removeClassName = (element, className) => {
  element.classList.remove(className);
}

let activeImage = 0;

const imageSlideShow = () => {
  const galleryImages = document.querySelectorAll('.home-gallery-container img');

    const numOfImages = galleryImages.length - 1;

    if (activeImage < numOfImages) {

      addClassName(galleryImages[activeImage], 'hidden');
      activeImage = activeImage + 1;
      removeClassName(galleryImages[activeImage], 'hidden');
      addClassName(galleryImages[activeImage], 'slide-image');

    } else if (activeImage === numOfImages) {

      addClassName(galleryImages[numOfImages], 'hidden');
      removeClassName(galleryImages[0], 'hidden');
      addClassName(galleryImages[0], 'slide-image');
      activeImage = 0;
    }
}

const intervalID = setInterval(imageSlideShow, 3000);

window.addEventListener('unload', () => {

  clearInterval(intervalID);

});






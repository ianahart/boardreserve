const featuresBox = document.querySelector('.details-features');
const featuresTrigger = document.querySelector('.features-header');
const minusIcon = document.querySelector('.features-minus-icon');
const plusIcon = document.querySelector('.features-plus-icon');

console.log(document.querySelector('.features-header'));

const removeClassName = (element, className) => {
  element.classList.remove(className);
}

const addClassName = (element, className) => {
    element.classList.add(className);
}

const hideAndShowElements = (elements, property) => {
    Array.from(elements).forEach((element) => {
    element.style.display = property;
  })
}

const toggleFeatures = (e) => {
  if (featuresBox.classList.contains('hidden-effect')) {

    removeClassName(minusIcon, 'hidden');
    addClassName(plusIcon, 'hidden');
    removeClassName(featuresBox, 'hidden-effect');

  hideAndShowElements(featuresBox.children, 'block');

  } else {

    addClassName(featuresBox, 'hidden-effect');
    addClassName(minusIcon, 'hidden');
    removeClassName(plusIcon, 'hidden');

  hideAndShowElements(featuresBox.children, 'none');

  }
};

hideAndShowElements(featuresBox.children, 'none');



featuresTrigger.addEventListener('click', toggleFeatures);

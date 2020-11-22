const questionMarkImage = document.querySelector('.contact-img-container img');
const aboutMeDiv = document.querySelector('.about-me');
const aboutCompanyDiv = document.querySelector('.about-company');

const addClassName = (element, className) => {
  element.classList.add(className);
}

const removeClassName = (element, className) => {
  element.classList.remove(className);
}

const isInViewport = function (elem) {
	const distance = elem.getBoundingClientRect();
	return (
		distance.top >= 0 &&
		distance.left >= 0 &&
		distance.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
		distance.right <= (window.innerWidth || document.documentElement.clientWidth)
	);
};

const showAboutContent = (e) => {
  if (isInViewport(questionMarkImage)) {
  removeClassName(aboutMeDiv, 'hidden')
    removeClassName(aboutCompanyDiv, 'hidden')
  }





}

window.addEventListener('scroll', showAboutContent);

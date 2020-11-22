const navTriggerOpen = document.querySelector('.account-settings-trigger');
const nav = document.querySelector('.user-links-container ');
const navTriggerArrow = document.querySelector('.nav-trigger-arrow');

const removeClassName = (element, className) => {

  element.classList.remove(className);
};

const addClassName = (element, className) => {

  element.classList.add(className);
}

const containsClassName = (element, className) => {

  if (element.classList.contains(className)) {

    return true;
  } else {

    return false;
  }
}

const openNavMenu = () => {

  if (containsClassName(nav, 'hidden')) {

     removeClassName(nav, 'slideout-profile-nav');
     addClassName(navTriggerArrow, 'rotate-arrow-in');
     removeClassName(navTriggerArrow, 'rotate-arrow-out');
     removeClassName(nav, 'hidden');
     addClassName(nav, 'slidein-profile-nav');

  } else {

    removeClassName(nav, 'slidein-profile-nav');
    addClassName(nav, 'slideout-profile-nav');
    removeClassName(navTriggerArrow, 'rotate-arrow-in');
    addClassName(navTriggerArrow, 'rotate-arrow-out');

    if (containsClassName(nav, 'slideout-profile-nav')) {

      setTimeout(() => {

        addClassName(nav, 'hidden');
      }, 500);
    }
  }
}

navTriggerOpen.addEventListener('click', openNavMenu);
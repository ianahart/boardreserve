const hamburger = document.querySelector('.hamburger');
const mainMenuList = document.querySelector('#navbar .main-menu-list');
const snowboardList = document.querySelector('.second-menu h3 ');
const secondMenuTrigger = document.querySelector('#navbar .second-menu');
const secondMenuList = document.querySelector('#navbar .second-menu-list');
const secondMenuCarrotIcon = document.querySelector('#navbar .carrot-icon');
const navBar = document.querySelector('#navbar');
const hamburgerLineOne = document.querySelector('#navbar .line-one');
const hamburgerLineTwo = document.querySelector('#navbar .line-two');
const profileTooltip = document.querySelector('#navbar .profile-tooltip');
const profileIcon = document.querySelector('#navbar .profile-list-item');
const feedTrigger = document.querySelector('#navbar .feed-menu .feed-trigger');
const feedMenu = document.querySelector('.feed-menu');
const feedMenuList = document.querySelector('.feed-menu-list');
const feedIcon = document.querySelector('.feed-icon');

const addClassToElement = (element, className) => {

  element.classList.add(className);
}

const removeClassFromElement = (element, className) => {

  element.classList.remove(className);
}

const showSecondMenu = (e) => {

      if (feedMenuList.style.display === 'block') {

      feedMenuList.style.display = '';

      addClassToElement(feedIcon, 'carrot-icon-up');
      removeClassFromElement(feedIcon, 'carrot-icon-down');
    }


  if (secondMenuList.style.display === 'block') {

      removeClassFromElement(secondMenuCarrotIcon, 'carrot-icon-down');

      addClassToElement(secondMenuCarrotIcon, 'carrot-icon-up');

      secondMenuList.style.display = '';

  } else {

      addClassToElement(secondMenuCarrotIcon, 'carrot-icon-down');

      secondMenuList.style.display = 'block';
  }

}

const animateHamburger = (lineOne, LineTwo) => {

  lineOne.classList.add('hamburger-animate-1');
  LineTwo.classList.add('hamburger-animate-2');

  lineOne.classList.remove('hamburger-normal-state-1');
  LineTwo.classList.remove('hamburger-normal-state-2');
}

const returnHamburgerState = (lineOne, lineTwo) => {

  lineOne.classList.add('hamburger-normal-state-1');
   lineTwo.classList.add('hamburger-normal-state-2');

   lineOne.classList.remove('hamburger-animate-1');
   lineTwo.classList.remove('hamburger-animate-2');
}


const showMobileMenu = (e) => {

  if (mainMenuList.style.display === 'block') {

      mainMenuList.style.display = '';

      if (snowboardList) {

        snowboardList.style.display = '';
      }

      if (feedTrigger) {

        feedTrigger.style.display = '';
      }

      if (secondMenuList) {

        secondMenuList.style.display = '';
      }


      addClassToElement(navBar, 'fadeout-mobile-menu');

      removeClassFromElement(navBar, 'fadein-mobile-menu');

      returnHamburgerState(hamburgerLineOne,hamburgerLineTwo)


  } else {

       addClassToElement(navBar, 'fadein-mobile-menu');

       removeClassFromElement(navBar, 'fadeout-mobile-menu');

      animateHamburger(hamburgerLineOne, hamburgerLineTwo);

      mainMenuList.style.display = 'block';

      if (snowboardList) {

        snowboardList.style.display = 'block';
      }

      if (feedTrigger) {
        feedTrigger.style.display = 'block';
      }

  }
}

const toggleMobileMenu = (e) => {

  const screenW = e.currentTarget.innerWidth;

  if (screenW > 771) {

    mainMenuList.style.display = '';

    if (snowboardList) {

      snowboardList.style.display = '';
    }

    if (feedTrigger) {

      feedTrigger.style.display = '';
    }

    if (feedMenuList) {

      if (feedMenuList.style.display === '' &&
        feedIcon.classList.contains('carrot-icon-down')
        ) {
            feedIcon.classList.remove('carrot-icon-down');
        }
    }

    if (secondMenuList) {

      if (secondMenuList.style.display === '' &&
            secondMenuCarrotIcon.classList.contains('carrot-icon-down'))
          {
            secondMenuCarrotIcon.classList.remove('carrot-icon-down');
          }

    }

  }

  if (screenW < 771) {

    if (secondMenuList) {

      secondMenuList.style.display = '';
    }

    if (feedMenuList) {

      feedMenuList.style.display = '';
    }


    returnHamburgerState(hamburgerLineOne, hamburgerLineTwo);
  }
}

const closeSecondMenu = (e) => {

  if (!e.target.classList.contains('second-menu-list')
    && e.target.tagName !== 'H3') {

      if (secondMenuList) {

        secondMenuList.style.display = '';
    }

    if (secondMenuCarrotIcon) {

      removeClassFromElement(secondMenuCarrotIcon, 'carrot-icon-down');

       addClassToElement(secondMenuCarrotIcon, 'carrot-icon-up');
    }

  }
}

const closeFeedMenu = (e) => {

  if (!e.target.classList.contains('feed-menu-list')
    && e.target.tagName !== 'H3') {

      if (feedMenuList) {

        feedMenuList.style.display = '';
    }

    if (feedIcon) {

      removeClassFromElement(feedIcon, 'carrot-icon-down');

       addClassToElement(feedIcon, 'carrot-icon-up');
    }

  }
}

const showProfileTooltip = () => {
  removeClassFromElement(profileTooltip, 'hidden');
}

const hideProfileTooltip = () => {
  addClassToElement(profileTooltip, 'hidden');
}


const showFeedMenu = () => {



    if (secondMenuList.style.display === 'block') {

      secondMenuList.style.display = '';

        addClassToElement(secondMenuCarrotIcon, 'carrot-icon-up');
        removeClassFromElement(secondMenuCarrotIcon, 'carrot-icon-down');

    }

   if (feedMenuList.style.display === 'block') {

      removeClassFromElement(feedIcon, 'carrot-icon-down');

      addClassToElement(feedIcon, 'carrot-icon-up');

      feedMenuList.style.display = '';

  } else {

      addClassToElement(feedIcon, 'carrot-icon-down');

      feedMenuList.style.display = 'block';
  }

}

window.addEventListener('resize', toggleMobileMenu);

hamburger.addEventListener('click', showMobileMenu);

if (secondMenuTrigger) {

secondMenuTrigger.addEventListener('click', showSecondMenu);
}

document.body.addEventListener('click', closeSecondMenu);
document.body.addEventListener('click', closeFeedMenu);

if (profileIcon) {

  profileIcon.addEventListener('mouseenter', showProfileTooltip);

  profileIcon.addEventListener('mouseleave', hideProfileTooltip);

}

if (feedTrigger) {

feedTrigger.addEventListener('click', showFeedMenu);

}

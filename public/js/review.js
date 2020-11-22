const slider = document.querySelector('#slider');
const currentRating = document.querySelector('#current-rating');
const ratingBars = document.querySelectorAll('.rating-bar');


const changeCurrentRating = (e) => {
  const currentValue = e.target.value;
  currentRating.textContent = currentValue;

}

const applyRatingsBar = (e) => {

  ratingBars.forEach((ratingBar) => {
    const rating = parseInt(
      ratingBar.getAttribute('data-rating') * 10
      );
     ratingBar.style.width = rating + "%";
  });
}
if (ratingBars.length > 0) {

  window.addEventListener('DOMContentLoaded', applyRatingsBar);
}


if (slider) {
slider.addEventListener('change', changeCurrentRating);
}

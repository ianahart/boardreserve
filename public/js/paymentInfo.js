const countryInputs = document.querySelectorAll('.country-input');
const stateDivs = document.querySelectorAll('.state');
const checkbox = document.querySelector('#checkbox');
const billingCountry = document.querySelector('[name="billing_country"]');
const billingPostalCode = document.querySelector('[name="billing_postal_code"]');
const billingCity = document.querySelector('[name="billing_city"]');
const billingState = document.querySelector('[name="billing_state"]');
const billingStreetAddress = document.querySelector('[name="billing_street_address"]');
const shippingCountry = document.querySelector('[name="shipping_country"]');
const shippingPostalCode = document.querySelector('[name="shipping_postal_code"]');
const shippingCity = document.querySelector('[name="shipping_city"]');
const shippingState = document.querySelector('[name="shipping_state"]');
const shippingStreetAddress = document.querySelector('[name="shipping_street_address"]');


const addClassName = (element, className) => {
  element.classList.add(className);
}

const removeClassName = (element, className) => {
  element.classList.remove(className);
}

const toggleStateInput = (e) => {
  const input = e.target.value;
  const trigger = "United States";

  if(input.toLowerCase().includes(trigger.toLowerCase())) {

    stateDivs.forEach((stateDiv) => {

      removeClassName(stateDiv, 'hidden');
    })

  } else {
    stateDivs.forEach((stateDiv) => {

      addClassName(stateDiv, 'hidden');
    })
  }
}

countryInputs.forEach((countryInput) => {

  countryInput.addEventListener('keyup', toggleStateInput);
});


const handleCheckbox = (e) => {
  if (e.target.checked) {

    shippingCountry.value = billingCountry.value;
    shippingPostalCode.value = billingPostalCode.value;
    shippingCity.value = billingCity.value;
    shippingState.value = billingState.value;
    shippingStreetAddress.value = billingStreetAddress.value;
  } else {

    shippingCountry.value = '';
    shippingPostalCode.value = '';
    shippingCity.value = '';
    shippingState.value = '';
    shippingStreetAddress.value = '';
  }
}

checkbox.addEventListener('click', handleCheckbox);



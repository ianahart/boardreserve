 const stripe = Stripe('pk_test_OhEfGqXzqbOw4jxZABmZ9mfn');
 const elements = stripe.elements();
 const cardElement = elements.create('card');
 const cardButton = document.getElementById('card-button');
 const cardHolderName = document.getElementById('card-holder-name');

    cardElement.mount('#card-element');

    cardButton.addEventListener('click', async (e) => {
    const { paymentMethod, error } = await stripe.createPaymentMethod(
        'card', cardElement, {
            billing_details: { name: cardHolderName.value }
        }
    );

    if (error) {
        console.log(error.message);
        console.log('fail');
    } else {
        // The card has been verified successfully...
        createPayment(paymentMethod.id, cardHolderName.value);
        console.log('success');
    }
});

   const form = document.getElementById('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
    });

    // Submit the form with the token ID.
    function createPayment(payment_id, name) {
    // Insert the token ID into the form so it gets submitted to the server
    const form = document.getElementById('form');
    const hiddenInputID = document.createElement('input');
    const hiddenInputName = document.createElement('input');
    hiddenInputName.setAttribute('type', 'hidden');
    hiddenInputName.setAttribute('name', 'card_holder');
    hiddenInputName.setAttribute('value', name);
    hiddenInputID.setAttribute('type', 'hidden');
    hiddenInputID.setAttribute('name', 'payment_id');
    hiddenInputID.setAttribute('value',payment_id);
    form.appendChild(hiddenInputName);
    form.appendChild(hiddenInputID);
    // Submit the form

    form.submit();

    }
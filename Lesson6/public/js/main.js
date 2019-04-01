window.onload = () => {

  const cart = new Cart('/api/cart/');

  $('.products__catalog').on('click', (evt) => {
    if (!evt.target.classList.contains('btn-buy')) {
      return;
    }
    evt.preventDefault();
    cart.addProduct(evt.target);
  });
};


// window.onload = () => {
//   const buyHandler = (evt) => {
//     if (evt.target.tagName !== 'BUTTON') {
//       return;
//     }
//     evt.preventDefault();
//     fetch('/api/cart/add', {
//       method: 'POST',
//       headers: {
//         "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
//       },
//       credentials: 'include',
//       body: "product_id=" + evt.target.dataset.id
//     })
//       .then((data) => console.log(data))
//       .catch((err) => console.log(err));
//   };
//
//   const catalogEl = document.querySelector('.products__catalog');
//   catalogEl.addEventListener('click', (evt) => buyHandler(evt));
// };

document.onload = () => {
  const buyHandler = (evt) => {
    if (evt.target.tagName !== 'button') {
      return;
    }
    fetch('/api/cart/add', {
      method: 'POST',
      headers: {
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
      },
      body: "id=" + evt.target.dataset.id
    })
      .then((json) => json)
      .then((data) => console.log(data))
      .catch((err) => console.log(err));
  };
  
  const catalogEl = document.querySelector('.products__catalog');
  catalogEl.addEventListener('click', (evt) => buyHandler(evt));
};
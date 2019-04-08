window.onload = () => {

  const cart = new Cart('/api/cart/');

  const buyHandler = (evt) => {
    if (!evt.target.classList.contains('btn-buy')) {
      return;
    }
    evt.preventDefault();
    cart.addProduct(evt.target);
  };

  $('.products__catalog').on('click', (evt) => buyHandler(evt));
  $('.product__view_wrap').on('click', (evt) => buyHandler(evt));

  //--------------order control-------------
  $('.order__status_change').on('click', (evt) => {
    evt.preventDefault();
    const orderId = $(evt.target).data('id');
    let orderStatusFull = $(evt.target).parent().find('option:selected').val();
    const orderStatus = parseInt($(evt.target).parent().find('option:selected').val());
    orderStatusFull = orderStatusFull.replace( /\d+\.\s/g, "" ).toLowerCase();
    $.post({
      url: '/api/order/changeOrderStatus',
      data: {
        postData: {
          id: orderId,
          status: orderStatus
        }
      },
      success: () => {
        $(evt.target).closest('.order_wrapper').find('.order_status').text(orderStatusFull);
      }
    })
  });

  const removeRetrieve = (evt) => {
    const $target = $(evt.target);
    const $tableRowProduct = $target.closest('.table_row');
    let action;
    if ($tableRowProduct.hasClass('table_row_deleted')) {
      action = 'retrieve';
    } else {
      action = 'remove';
    }
    const orderProductId = $target.data('id');
    $.post({
      url: `/api/order/${action}Product?id=${orderProductId}`,
      data: {
        postData: {
          id: orderProductId
        }
      },
      success: () => {
        $tableRowProduct.toggleClass('table_row_deleted');
        $target.text(action);
      }
    })
  };

  $('.order__product_retrieve_remove').on('click', (evt) => {
    removeRetrieve(evt);
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
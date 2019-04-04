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

  $('.order__product_remove').on('click', (evt) => {
    const $target = $(evt.target);
    const orderProductId = $target.data('id');
    $.post({
      url: `/api/order/removeProduct?id=${orderProductId}`,
      data: {
        postData: {
          id: orderProductId
        }
      },
      success: () => {
        $target.closest('.table_row').addClass('table_row_deleted');
        $target.text('Retrieve');
      }
    })
  });

  $('.order__product_retrieve').on('click', (evt) => {
    const $target = $(evt.target);
    const orderProductId = $target.data('id');
    $.post({
      url: '/api/order/retrieveProduct?id=${orderProductId}',
      data: {
        postData: {
          id: orderProductId
        }
      },
      success: () => {
        $target.closest('.table_row').removeClass('table_row_deleted');
        $target.text('Remove');
      }
    })
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
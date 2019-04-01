class Cart {
  constructor(source, container = '#cart') {
    this.source = source;
    this.container = container;
    this.countGoods = 0; // Общее кол-во товаров в корзине
    this.amount = 0; // Общая стоимость товаров в корзине
    this.cartItems = []; // Все товары
    this._init();
  }

  _init() {
    this._render();
    this._addEventHandlers();
    $.getJSON({
      url: this.source + 'get',
      success: (data) => {
        for (let product of data.data) {
          const newProduct = this._convertData(product);
          this.cartItems.push(newProduct);
          this._renderItem(newProduct);
          this._changeQuantity(newProduct, +product.quantity, false)
        }
        this._renderSum();
      }
    });
  }
  
  _convertData(product) {
    const numberField = ['id', 'quantity', 'price'];
    const newProduct = [];
    for (const productKey in product) {
      if (numberField.includes(productKey)) {
        newProduct[productKey] = +product[productKey];
      } else {
        newProduct[productKey] = product[productKey];
      }
    }
    newProduct['quantity'] = 0;
    return newProduct;
  }

  _render() {
    let $cartListDiv = $('<div/>', {
      class: 'cart-items-list'
    });
    let $cartItemsDiv = $('<div/>', {
      class: 'cart-items-wrap'
    });
    let $totalGoods = $('<div/>', {
      class: 'cart-summary sum-goods'
    });
    let $totalPrice = $('<div/>', {
      class: 'cart-summary sum-price'
    });
    let $buttonOrder = $('<button/>', {
      class: 'btn btn-primary',
      text: 'Оформить заказ'
    });
    $buttonOrder.on('click', () => this._sendToServer({}, 'order'));
    const $cartHeader = $('<div/>', {
      class: 'cart-header'
    }).text(`Корзина (${this.countGoods})`);
    $(this.container).append($cartHeader);
    $cartItemsDiv.appendTo($cartListDiv);
    $totalGoods.appendTo($cartListDiv);
    $totalPrice.appendTo($cartListDiv);
    $buttonOrder.appendTo($cartListDiv);
    $cartListDiv.appendTo($(this.container));
  }

  _addEventHandlers() {
    $(this.container).on('click', '.reduce-quantity', evt =>
      this._onChangeQuantity(evt, -1));
    $(this.container).on('click', '.increase-quantity', evt =>
      this._onChangeQuantity(evt, 1));
    $(this.container).on('click', '.delete-product', evt =>
      this._onChangeQuantity(evt, 0, true));
  }

  _renderItem(product) {
    let $container = $('<div/>', {
      class: 'cart-item',
      'data-product': product.id
    });
    $container.append($(`<p class="product-name">${product.name}</p>`));

    const $quantity = $('<div/>', {
      class: 'quantity-wrap'
    });

    $quantity.append($(`<button class="btn-quantity reduce-quantity">-</button>`));
    $quantity.append($(`<p class="product-quantity">${product.quantity}</p>`));
    $quantity.append($(`<button class="btn-quantity increase-quantity">+</button>`));
    $container.append($quantity);

    $container.append($(`<p class="product-price">${product.price} руб.</p>`));
    $container.append($(`<button class="btn-quantity delete-product">X</button>`));
    $container.appendTo($('.cart-items-wrap'));
  }

  _renderSum() {
    $('.cart-header').text(`Корзина (${this.countGoods})`);
    $('.sum-goods').text(`Всего товаров в корзине: ${this.countGoods}`);
    $('.sum-price').text(`Общая сумма: ${this.amount} руб.`);
  }

  _updateCart(product) {
    let $container = $(`div[data-product="${product.id}"]`);
    $container.find('.product-quantity').text(product.quantity);
    $container.find('.product-price').text(`${product.quantity * product.price} руб.`);
  }

  _getCartItem(id) {
    return this.cartItems.find(product => product.id === id);
  }

  addProduct(element) {
    let productId = +$(element).data('id');
    let find = this._getCartItem(productId);
    if (find) {
      this._changeQuantity(find, 1);
    } else {
      let product = {
        id: productId,
        name: $(element).data('name'),
        price: +$(element).data('price'),
        quantity: 1
      };
      this.cartItems.push(product);
      this._renderItem(product);
      this.amount += product.price;
      this.countGoods += product.quantity;
      this._sendToServer(this._getPostData(product), 'add');
    }
    this._renderSum();
  }

  _getPostData(product) {
    return {
      product_id: product.id,
      quantity: product.quantity
    }
  }

  _sendToServer(postData, methodName) {
    $.post({
      url: this.source + methodName,
      data: {
        postData: postData
      },
      success: (data) => {
        console.log(data);

        if (data.location) {
          window.location.replace(data.location);
        }
      },
      error: (error) => {
        console.log(error);

        // error = JSON.parse(error);
        if (error.location) {
          window.location.replace(error.location);
        }
      },
    })
  }

  _getEventProductId(evt) {
    return $(evt.target).closest('.cart-item').data('product');
  }

  _onChangeQuantity(evt, quantity = 1, deleteItem = false) {
    let find = this._getCartItem(this._getEventProductId(evt));
    this._changeQuantity(find, deleteItem ? -find.quantity : quantity);
  }

  _changeQuantity(cartItem, quantity, sendServer = true) {
    cartItem.quantity += quantity;
    this.countGoods += quantity;
    this.amount += cartItem.price * quantity;
    let method = '';
    if (cartItem.quantity === 0) {
      this._remove(cartItem);
      method = 'delete';
    } else {
      this._updateCart(cartItem);
      method = 'update';
    }
    if (sendServer) {
      this._sendToServer(this._getPostData(cartItem), method);
    }
    this._renderSum();
  }

  _remove(cartItem) {
    this.cartItems.splice(this.cartItems.indexOf(cartItem), 1);
    let $container = $(`div[data-product="${cartItem['id']}"]`);
    $container.remove();
  }
}
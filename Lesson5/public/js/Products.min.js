
Vue.component('product-list', {
  data() {
    return {
      products: [],
      pageNumber: 0,
      countProductsOnPage: 9,
      countProductsShow: 9,
      countProducts: 0,
    }
  },
  methods: {
    filter(userInput) {
      const regExp = new RegExp(`${userInput}`, 'i');
      this.products = this.products.filter((product) => regExp.test(product.product_name));
    },
    getProducts(fromPage = 0, addToExisting = false) {
      const limitFrom = fromPage * this.countProductsShow;
      const url = `/index.php?ctrl=api_product&action=getproducts&from=${limitFrom}&to=${this.countProductsShow}`;
      this.$parent.getJson(url)
        .then(result => {
          if (!result.result) {
            console.log(result.message);
            return;
          }
          if (addToExisting) {
            this.products = [...this.products, ...result.data]
          } else {
            this.products = result.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    getCountProducts() {
      const url = `/index.php?ctrl=api_product&action=countproducts`;
      this.$parent.getJson(url)
        .then(data => {
          this.countProducts = data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    changePage(direction) {
      this.pageNumber += direction;
      this.getProducts(this.pageNumber);
    },
    moreProducts() {
      this.pageNumber++;
      this.getProducts(this.pageNumber, true);
    },
    allProducts() {
      this.countProductsShow = this.countProducts;
      this.getProducts();
    }
  },
  mounted() {
    this.getProducts();
    this.getCountProducts();
  },
  template: `<div class="products catalog-container container" >
                <div v-for="product of products" :key="product.id_product">
                    <product-item :product="product"></product-item>
                </div>
            </div>`
});

Vue.component('product-item', {
  props: [
    'product',
    'img'
  ],
  template: `
<div class="product-card">
    <a class="product-card-link" href="single-page.html">
        <div class="product-img-wrap">
            <img class="product-img" :src="product.img_src" :alt="product.name">
            <div class="product-img-mask"></div>
        </div>

        <div class="product-card-info">
            <p class="product-name">{{product.name}}</p>

            <div class="product-bottom">
                <p class="product-price">$ {{product.price}}</p>
                <div class="product-rating">
                    <i class="fas fa-star rating-star"></i>
                    <i class="fas fa-star rating-star"></i>
                    <i class="fas fa-star rating-star"></i>
                    <i class="fas fa-star rating-star"></i>
                    <i class="far fa-star rating-star"></i>
                </div>
            </div>
        </div>
    </a>
    <div class="parent-product-card">
        <div class="add-cart-wrap" @click="$emit('add-to-cart',product)">
            <a href="#" class="product-card-hover">Add to Cart</a>
        </div>
        <div class="product-card-hover-buttons">
            <a href="#" class="product-card-hover reload"></a>
            <a href="#" class="product-card-hover favorite"></a>
        </div>
    </div>
</div>
`
});
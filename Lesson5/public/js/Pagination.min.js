Vue.component('catalog-pagination', {
  data() {
    return {
      pages: [1, 2, 3, 4]
    }
  },
  template: `
              <div class="pagination">
                <a href="#" class="pagination-link pagination-link-prev"><i class="fas fa-angle-left"></i></a>
                <div v-for="page of pages" :key="page">
                    <a href="#" class="pagination-link">{{ page }}</a>
                </div>
                <a href="#" class="pagination-link pagination-link-next"><i class="fas fa-angle-right"></i></a>
            </div>`
});
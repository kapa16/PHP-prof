class Gallery {
  constructor(galleryContainerEl) {
    this.settings = {
      galleryWrapperClass: 'gallery__wrapper',
      galleryImageClass: 'gallery__image',
      fullSizeImageWindowClass: 'gallery__modal-windows',
      fullSizeImageScreenClass: 'gallery__modal-screen',
      fullSizeImageClass: 'gallery__full-image',
      fullSizeImageCloseButtonClass: 'gallery__close-button',
      fullSizeImageCloseButtonSrc: 'img/close.png',

    };
    this.images = [];
    this.galleryContainerEl = galleryContainerEl;
    this.galleryWrapperEl = document.querySelector(`.${this.settings.galleryWrapperClass}`);
    this._addEventOpenFullImage();
  }

  /**
   * переопределяет настройки по-умолчанию
   * @param {Object} settings - объект с настройками
   */
  init(settings) {
    Object.assign(this.settings, settings);
  }

  _addEventOpenFullImage() {
    this.galleryWrapperEl.addEventListener('click', evt => this._onClickImagePreview(evt));
  }

  _onClickImagePreview(evt) {
    const elementClick = evt.target;
    if (elementClick.tagName !== 'IMG') {
      return;
    }
    this._showFullSizeImage(elementClick);
  }

  _onClickCLoseButton() {
    document.querySelector(`.${this.settings.fullSizeImageWindowClass}`).remove();
  }

  _showFullSizeImage(elem) {
    const modalWindowEl = this._createModalWindow();
    const modalScreenEl = this._createModalScreen();
    const closeBtnEl = this._createCloseBtn();
    const fullSizeImageEl = this._createFullSizeImage(elem);

    closeBtnEl.addEventListener('click', () => this._onClickCLoseButton());

    this.galleryContainerEl.appendChild(modalWindowEl);
    modalWindowEl.appendChild(modalScreenEl);
    modalWindowEl.appendChild(closeBtnEl);
    modalWindowEl.appendChild(fullSizeImageEl);
  }

  _createModalWindow() {
    const modalWindowEl = document.createElement('div');
    modalWindowEl.classList.add(this.settings.fullSizeImageWindowClass);
    return modalWindowEl;
  }

  _createModalScreen() {
    const modalWindowEl = document.createElement('div');
    modalWindowEl.classList.add(this.settings.fullSizeImageScreenClass);
    return modalWindowEl;
  }

  _createCloseBtn() {
    const closeBtnEl = new Image();
    closeBtnEl.classList.add(this.settings.fullSizeImageCloseButtonClass);
    closeBtnEl.src = this.settings.fullSizeImageCloseButtonSrc;
    closeBtnEl.alt = 'close button';
    return closeBtnEl;
  }

  _createFullSizeImage(elem) {
    const fullSizeImageEl = new Image();
    fullSizeImageEl.classList.add(this.settings.fullSizeImageClass);
    fullSizeImageEl.src = elem.src;
    fullSizeImageEl.alt = elem.alt;
    return fullSizeImageEl;
  }
}
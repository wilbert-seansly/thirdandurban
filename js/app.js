/**
 * NodeList.forEach polyfill
 * https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach
 */
if (window.NodeList && !NodeList.prototype.forEach) {
  NodeList.prototype.forEach = function (callback, argument) {
    argument = argument || window;
    for (var i = 0; i < this.length; i++) {
      callback.call(argument, this[i], i, this);
    }
  };
}

var elementMover = {
  nest: function (a, b) {
    a = document.getElementById(a);
    b = document.getElementById(b);
    if (a && b) {
      a.appendChild(b);
    }
  }
};
elementMover.nest('post-who-we-are', 'post-where-we-work');


var Menu = {
  htmlNode: null,
  menuToggleNodes: null,
  menuItems: null,
  init: function () {
    this.htmlNode = document.querySelector('html');
    this.menuToggleNodes = document.querySelectorAll('.toggle-menu--open, .toggle-menu--close');
    this.menuItems = document.querySelectorAll('#site-navigation a');

    this.bind();
  },
  bind: function () {
    var self = this;

    Array.prototype.forEach.call(this.menuToggleNodes, function (n) {
      n.addEventListener('click', self.handleToggleClick.bind(self));
    });

    Array.prototype.forEach.call(this.menuItems, function (n) {
      n.addEventListener('click', self.handleItemClick.bind(self));
    });
  },
  handleToggleClick: function (e) {
    this.toggleMenu();
  },
  handleItemClick: function (e) {
    this.hideMenu();
  },
  toggleMenu: function () {
    if (this.htmlNode.className.indexOf('menu--open') > -1) { // menu is open
      this.hideMenu();
    }
    else {
      this.showMenu();
    }
  },
  hideMenu: function () {
    var htmlNode = this.htmlNode;
    htmlNode.className = htmlNode.className.replace('menu--open', 'menu--close');

    setTimeout(function () {
      htmlNode.className = htmlNode.className.replace('menu--close', '');
    }, 500);
  },
  showMenu: function () {
    if (this.htmlNode.className.indexOf('menu--close') == -1) {
      this.htmlNode.className += ' menu--open';
    }
    else {
      this.htmlNode.className = this.htmlNode.className.replace('menu--close', 'menu--open');
    }
  }
};
Menu.init();


/**
 *
 * STALKER
 *
 */
var Stalker = {
  node: null,
  threshold: 170,
  STALKING: ' stalking',
  init: function () {
    this.node = document.querySelector('.site-header__stalker');
    this.bind();
  },
  bind: function (e) {
    window.addEventListener('scroll', this.handleScroll.bind(this));
  },
  handleScroll: function (e) {
    if (window.pageYOffset > this.threshold && !this.stalking) {
      document.body.className += this.STALKING;
      this.stalking = true;
    }
    else if (window.pageYOffset <= this.threshold && this.stalking) {
      this.stalking = false;
      document.body.className = document.body.className.replace(this.STALKING, '');
    }
  }
};

Stalker.init();


// DETECTOR: Detect if browser is IE but not EDGE
// Get IE or Edge browser version
var isIE = function () {
  /**
   * detect IE
   * returns version of IE or false, if browser is not Internet Explorer
   */
  function detectIE() {
    var ua = window.navigator.userAgent;

    // Test values; Uncomment to check result …

    // IE 10
    // ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

    // IE 11
    // ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

    // Edge 12 (Spartan)
    // ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

    // Edge 13
    // ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
      // IE 10 or older => return version number
      return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
      // IE 11 => return version number
      var rv = ua.indexOf('rv:');
      return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
      // Edge (IE 12+) => return version number
      return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }

    // other browser
    return false;
  }

  var ie = detectIE();
  if (ie && ie < 11) {
    document.body.className += ' ie';
  }
  if (ie && ie === 11) {
    document.body.className += ' ie-11';
  }
};
isIE();

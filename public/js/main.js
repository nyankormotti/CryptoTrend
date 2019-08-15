/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  // ===========================================================
  // バリデーションエラー時に、エラー個所まで画面をスクロールさせる(トップページのお問い合わせ機能のみに適用)
  // ===========================================================
  var targetoffset = null;
  var err = $(".js-u-err__msg__main");

  if (err.length !== 0) {
    targetoffset = err.offset();
    var hight = $("html,body");
    hight.animate({
      scrollTop: targetoffset.top - 80
    }, {
      queue: false
    });
  } // ===========================================================
  // 続きを読むボタン(関連ニュース画面)
  // ===========================================================
  // 分割したい個数を入力


  var division = 5; // 要素の数を数える

  var divlength = $('.p-news__content__article').length; //分割数で割る

  dlsizePerResult = divlength / division; //分割数 刻みで後ろにmorelinkを追加する

  for (i = 1; i <= dlsizePerResult; i++) {
    $('.p-news__content__article').eq(division * i - 1).after('<span class="morelink link' + i + '">もっと見る</span>');
  } //最初のli（分割数）と、morelinkを残して他を非表示


  $('.p-news__content__article,.morelink').hide();

  for (j = 0; j < division; j++) {
    $('.p-news__content__article').eq(j).show();
  }

  $('.morelink.link1').show(); //morelinkにクリック時の動作

  $('.morelink').click(function () {
    //何個目のmorelinkがクリックされたかをカウント
    index = $(this).index('.morelink'); //(クリックされたindex +2) * 分割数 = 表示数

    for (k = 0; k < (index + 2) * division; k++) {
      $('.p-news__content__article,.morelink').eq(k).fadeIn();
    } //一旦全てのmorelink削除


    $('.morelink').hide(); //次のmorelink(index+1)を表示

    $('.morelink').eq(index + 1).show();
  }); // ===========================================================
  //処理完了時のメッセージ表示
  // ===========================================================

  var $jsShowMsg = $('#js-msg');
  var msg = $jsShowMsg.text();

  if (msg.replace(/^[\s ]+|[\s ]+$/g, "").length) {
    $jsShowMsg.slideToggle('slow');
    setTimeout(function () {
      $jsShowMsg.slideToggle('slow');
    }, 5000);
  } // ===========================================================
  // アコーディオンメニュー
  // ===========================================================


  $('.toggle_switch').on('click', function () {
    $(this).toggleClass('open');
    $(this).next('.toggle_contents').slideToggle();
  }); // ===========================================================
  // モーダルウィンドウ(退会ボタンクリック時)
  // ===========================================================

  $('#openModal').click(function () {
    $('#modalArea').fadeIn();
  });
  $('#closeModal , #modalBg').click(function () {
    $('#modalArea').fadeOut();
  });
});

/***/ }),

/***/ 3:
/*!************************************!*\
  !*** multi ./resources/js/main.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/resources/js/main.js */"./resources/js/main.js");


/***/ })

/******/ });
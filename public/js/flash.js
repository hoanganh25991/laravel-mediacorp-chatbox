/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.l = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// identity function for calling harmory imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };

/******/ 	// define getter function for harmory exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		Object.defineProperty(exports, name, {
/******/ 			configurable: false,
/******/ 			enumerable: true,
/******/ 			get: getter
/******/ 		});
/******/ 	};

/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};

/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

"use strict";
eval("'use strict';\n\nvar endAnimationSignal = 'animationend';\nvar animation = 'fadeOutRight';\nvar flash = function flash(msg, level) {\n\tvar flashDiv = $('.alert');\n\tlevel = level || 'info';\n\n\tflashDiv.html(msg);\n\tflashDiv.attr('class', 'alert alert-' + level);\n\n\tif (level == 'important') return;\n\n\tvar interval = setInterval(function () {\n\t\tflashDiv.addClass('animated  ' + animation);\n\t\tclearInterval(interval);\n\t}, 3000);\n\n\tflashDiv.one(endAnimationSignal, function () {\n\t\t// flashDiv.removeClass();\n\t\tconsole.log('animation end');\n\t});\n};\nwindow.flash = flash;//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy9yZXNvdXJjZXMvYXNzZXRzL2pzL2ZsYXNoLmpzPzdkNGYiXSwic291cmNlc0NvbnRlbnQiOlsiJ3VzZSBzdHJpY3QnO1xuXG52YXIgZW5kQW5pbWF0aW9uU2lnbmFsID0gJ2FuaW1hdGlvbmVuZCc7XG52YXIgYW5pbWF0aW9uID0gJ2ZhZGVPdXRSaWdodCc7XG52YXIgZmxhc2ggPSBmdW5jdGlvbiBmbGFzaChtc2csIGxldmVsKSB7XG5cdHZhciBmbGFzaERpdiA9ICQoJy5hbGVydCcpO1xuXHRsZXZlbCA9IGxldmVsIHx8ICdpbmZvJztcblxuXHRmbGFzaERpdi5odG1sKG1zZyk7XG5cdGZsYXNoRGl2LmF0dHIoJ2NsYXNzJywgJ2FsZXJ0IGFsZXJ0LScgKyBsZXZlbCk7XG5cblx0aWYgKGxldmVsID09ICdpbXBvcnRhbnQnKSByZXR1cm47XG5cblx0dmFyIGludGVydmFsID0gc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xuXHRcdGZsYXNoRGl2LmFkZENsYXNzKCdhbmltYXRlZCAgJyArIGFuaW1hdGlvbik7XG5cdFx0Y2xlYXJJbnRlcnZhbChpbnRlcnZhbCk7XG5cdH0sIDMwMDApO1xuXG5cdGZsYXNoRGl2Lm9uZShlbmRBbmltYXRpb25TaWduYWwsIGZ1bmN0aW9uICgpIHtcblx0XHQvLyBmbGFzaERpdi5yZW1vdmVDbGFzcygpO1xuXHRcdGNvbnNvbGUubG9nKCdhbmltYXRpb24gZW5kJyk7XG5cdH0pO1xufTtcbndpbmRvdy5mbGFzaCA9IGZsYXNoO1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyByZXNvdXJjZXMvYXNzZXRzL2pzL2ZsYXNoLmpzIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9");

/***/ }
/******/ ]);
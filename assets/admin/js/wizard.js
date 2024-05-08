"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
var Wizard = {
  data: function data() {
    return {
      loaded: false,
      screen: 0,
      hasCache: false,
      currentPage: "welcome",
      userType: "normal",
      hasConsent: true,
      steps: [{
        key: "welcome",
        name: "Welcome",
        isComplete: false
      }, {
        key: "widgets",
        name: "Widgets",
        isComplete: false
      }, {
        key: "features",
        name: "Features",
        isComplete: false
      }, {
        key: "bepro",
        name: "Be a pro!",
        isComplete: false
      }, {
        key: "contribute",
        name: "Contribute",
        isComplete: false
      }, {
        key: "congrats",
        name: "Congrats",
        isComplete: false
      }],
      widgetList: [],
      disabledWidgets: [],
      featureList: [],
      disabledFeatures: [],
      settings: {
        welcome: {
          userType: null
        },
        widgets: [],
        features: null,
        contribute: false,
        all: [],
        checkedWidgets: []
      },
      widgetMore: true
    };
  },
  mounted: function mounted() {
    this.fetchCache();
    this.getCurrentPage();
  },
  methods: {
    fetchWidgetData: function fetchWidgetData() {
      var _this = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var url;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              url = window.HappyWizard.apiBase + "/widgets/all/";
              _context.next = 3;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data) {
                  _this.widgetList = data.all;
                  _this.disabledWidgets = data.disabled;
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 3:
            case "end":
              return _context.stop();
          }
        }, _callee);
      }))();
    },
    fetchCache: function fetchCache() {
      var _this2 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2() {
        var url;
        return _regeneratorRuntime().wrap(function _callee2$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              url = window.HappyWizard.apiBase + "/wizard/cache";
              _context2.next = 3;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data.data) {
                  if (data.data.steps) {
                    _this2.steps = data.data.steps;
                  }
                  if (data.data.currentPage) {
                    _this2.currentPage = data.data.currentPage;
                  }
                  if (data.data.userType) {
                    _this2.userType = data.data.userType;
                  }
                  if (data.data.widgets) {
                    _this2.widgetList = data.data.widgets;
                  }
                  if (data.data.widgets_disabled) {
                    _this2.disabledWidgets = data.data.widgets_disabled;
                  }
                  if (data.data.features) {
                    _this2.featureList = data.data.features;
                  }
                  if (data.data.features_disabled) {
                    _this2.disabledFeatures = data.data.features_disabled;
                  }
                  _this2.loaded = true;
                } else {
                  _this2.fetchPreset(_this2.userType);
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 3:
            case "end":
              return _context2.stop();
          }
        }, _callee2);
      }))();
    },
    fetchPreset: function fetchPreset(userType) {
      var _this3 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee3() {
        var url;
        return _regeneratorRuntime().wrap(function _callee3$(_context3) {
          while (1) switch (_context3.prev = _context3.next) {
            case 0:
              url = window.HappyWizard.apiBase + "/wizard/preset/" + userType;
              _context3.next = 3;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data) {
                  _this3.widgetList = data.widgets.all;
                  _this3.disabledWidgets = data.widgets.disabled;
                  _this3.featureList = data.features.all;
                  _this3.disabledFeatures = data.features.disabled;
                }
                _this3.loaded = true;
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 3:
            case "end":
              return _context3.stop();
          }
        }, _callee3);
      }))();
    },
    saveWizardData: function saveWizardData() {
      var _arguments = arguments,
        _this4 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee4() {
        var mode, url, data;
        return _regeneratorRuntime().wrap(function _callee4$(_context4) {
          while (1) switch (_context4.prev = _context4.next) {
            case 0:
              mode = _arguments.length > 0 && _arguments[0] !== undefined ? _arguments[0] : '';
              url = window.HappyWizard.apiBase + "/wizard/save";
              data = {
                'widget': _this4.disabledWidgets,
                'features': _this4.disabledFeatures,
                'consent': _this4.consent ? 'yes' : 'no'
              };
              if (mode == "cache") {
                url = window.HappyWizard.apiBase + "/wizard/save-cache";
                data = {
                  'currentPage': _this4.currentPage,
                  'userType': _this4.userType,
                  'steps': _this4.steps,
                  'widgets': _this4.widgetList,
                  'widgets_disabled': _this4.disabledWidgets,
                  'features': _this4.featureList,
                  'features_disabled': _this4.disabledFeatures,
                  'consent': _this4.hasConsent ? 'yes' : 'no'
                };
              }
              _context4.next = 6;
              return fetch(url, {
                method: "POST",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                },
                body: JSON.stringify(data),
                contentType: "application/json; charset=utf-8"
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data && data.status === 200) {
                  if (mode === "cache") {} else {
                    window.open(window.HappyWizard.haAdmin, "_self");
                  }
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 6:
            case "end":
              return _context4.stop();
          }
        }, _callee4);
      }))();
    },
    endWizard: function endWizard() {
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee5() {
        var agee, url;
        return _regeneratorRuntime().wrap(function _callee5$(_context5) {
          while (1) switch (_context5.prev = _context5.next) {
            case 0:
              agee = confirm('Head’s up. This action is non reversible and you won\’t be able to see this wizard again. Proceed?');
              if (!agee) {
                _context5.next = 5;
                break;
              }
              url = window.HappyWizard.apiBase + "/wizard/skip";
              _context5.next = 5;
              return fetch(url, {
                method: "POST",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data && data.status === 200) {
                  window.open(window.HappyWizard.haAdmin, "_self");
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 5:
            case "end":
              return _context5.stop();
          }
        }, _callee5);
      }))();
    },
    setUserType: function setUserType(type) {
      this.userType = type;
      this.fetchPreset(type);
    },
    setTab: function setTab(screen) {
      if (screen) {
        if (screen == 'buypro') {
          window.open('https://happyaddons.com/go/get-pro', '_blank').focus();
        } else if (screen == 'done') {
          this.saveWizardData();
        } else {
          this.setStepComplete(this.currentPage);
          this.currentPage = screen;
          this.screen = screen;
        }
        this.saveWizardData("cache");
      }
    },
    setStepComplete: function setStepComplete(step) {
      var _iterator = _createForOfIteratorHelper(this.steps),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var elem = _step.value;
          if (elem.key == step) {
            elem.isComplete = true;
            break;
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    },
    revealWidgetList: function revealWidgetList() {
      this.widgetMore = false;
    },
    getCurrentPage: function getCurrentPage() {
      var _iterator2 = _createForOfIteratorHelper(this.steps),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var elem = _step2.value;
          if (elem.isComplete == false) {
            this.currentPage = elem.key;
            break;
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      return this.currentPage;
    },
    goNext: function goNext(screen) {
      this.setTab(screen);
    },
    allAdd: function allAdd(key) {
      var modified = this.widgetList[key];
      var localThis = this;
      Object.keys(modified).forEach(function (item) {
        modified[item].is_active = true;
        localThis.isActive(modified[item].slug, false);
      });
      if (this.settings.all.indexOf(key) === -1) {
        this.settings.all.push(key);
      }
      return modified;
    },
    allRemove: function allRemove(key) {
      var modified = this.widgetList[key];
      var localThis = this;
      Object.keys(modified).forEach(function (item) {
        modified[item].is_active = false;
        localThis.isActive(modified[item].slug, true);
      });
      this.settings.all = this.settings.all.filter(function (value, index, arr) {
        return value != key;
      });
      return modified;
    },
    isActive: function isActive(key, stat) {
      if (stat === true) {
        if (this.disabledWidgets.indexOf(key) === -1) {
          this.disabledWidgets.push(key);
        }
      } else {
        this.disabledWidgets = this.disabledWidgets.filter(function (value, index, arr) {
          return value != key;
        });
      }
    },
    isFeatureActive: function isFeatureActive(key, stat) {
      if (stat === true) {
        if (this.disabledFeatures.indexOf(key) === -1) {
          this.disabledFeatures.push(key);
        }
      } else {
        this.disabledFeatures = this.disabledFeatures.filter(function (value, index, arr) {
          return value != key;
        });
      }
    },
    makeTitle: function makeTitle(slug) {
      var title = slug.replace(/-/g, " ").replace("and", "&");
      return title.charAt(0).toUpperCase() + title.slice(1);
    },
    makeLabel: function makeLabel(isPro) {
      if (isPro) {
        return "PRO";
      }
      return "FREE";
    },
    sortByTitle: function sortByTitle(list) {
      return list.sort(function (a, b) {
        return a['title'] < b['title'] ? -1 : 1;
      });
    }
  },
  watch: {
    "settings.checkedWidgets": function settingsCheckedWidgets(val) {},
    "settings.all": function settingsAll(val) {},
    hasConsent: function hasConsent(val) {}
  },
  computed: {}
};
var app = Vue.createApp(Wizard);
app.config.globalProperties.window = window;
app.component("ha-step", {
  props: {
    active: String,
    complete: Boolean,
    step: String,
    title: String,
    index: Number
  },
  emits: ["setTab"],
  computed: {
    isActive: function isActive() {
      return this.active == this.step ? true : false;
    }
  },
  methods: {
    handleClick: function handleClick(step) {
      if (this.complete) {
        this.$emit('setTab', step);
      }
    }
  },
  template: "<div class=\"ha-stepper__step\" :class=\"{ 'is-complete': this.complete, 'is-active': this.isActive }\" @click=\"handleClick(step)\">\n\t<button class=\"ha-stepper__step-label-wrapper\">\n\t\t<div class=\"ha-stepper__step-icon\">\n\t\t\t<span class=\"ha-stepper__step-number\">{{index}}</span>\n\t\t\t<svg width=\"15\" height=\"11\" viewBox=\"0 0 15 11\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n\t\t\t\t<path d=\"M5.09467 10.784L0.219661 5.98988C-0.0732203 5.70186 -0.0732203 5.23487 0.219661 4.94682L1.2803 3.90377C1.57318 3.61572 2.04808 3.61572 2.34096 3.90377L5.625 7.13326L12.659 0.216014C12.9519 -0.0720048 13.4268 -0.0720048 13.7197 0.216014L14.7803 1.25907C15.0732 1.54709 15.0732 2.01408 14.7803 2.30213L6.15533 10.784C5.86242 11.072 5.38755 11.072 5.09467 10.784Z\" fill=\"white\"/>\n\t\t\t</svg>\n\t\t</div>\n\t\t<div class=\"ha-stepper__step-text\">\n\t\t\t<span class=\"ha-stepper__step-label\">{{title}}</span>\n\t\t</div>\n\t</button>\n</div>\n<div class=\"ha-stepper__step-divider\">\n<svg width=\"20\" height=\"21\" viewBox=\"0 0 20 21\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n<path d=\"M14.2218 4.80762C13.8313 4.4171 13.1981 4.4171 12.8076 4.80762C12.4171 5.19815 12.4171 5.83131 12.8076 6.22184L14.2218 4.80762ZM18.4853 10.4853L19.1924 11.1924L19.8995 10.4853L19.1924 9.77818L18.4853 10.4853ZM12.8076 14.7487C12.4171 15.1393 12.4171 15.7724 12.8076 16.163C13.1981 16.5535 13.8313 16.5535 14.2218 16.163L12.8076 14.7487ZM7.19238 4.80762C6.80186 4.4171 6.16869 4.4171 5.77817 4.80762C5.38764 5.19814 5.38764 5.83131 5.77817 6.22183L7.19238 4.80762ZM11.4558 10.4853L12.1629 11.1924L12.87 10.4853L12.1629 9.77818L11.4558 10.4853ZM5.77817 14.7487C5.38764 15.1393 5.38764 15.7724 5.77817 16.163C6.16869 16.5535 6.80186 16.5535 7.19238 16.163L5.77817 14.7487ZM12.8076 6.22184L17.7782 11.1924L19.1924 9.77818L14.2218 4.80762L12.8076 6.22184ZM17.7782 9.77818L12.8076 14.7487L14.2218 16.163L19.1924 11.1924L17.7782 9.77818ZM5.77817 6.22183L10.7487 11.1924L12.1629 9.77818L7.19238 4.80762L5.77817 6.22183ZM10.7487 9.77818L5.77817 14.7487L7.19238 16.163L12.1629 11.1924L10.7487 9.77818Z\" fill=\"currentColor\"/>\n</svg>\n</div>"
});
app.component("ha-nav", {
  props: {
    prev: String,
    next: String,
    done: String,
    bepro: String
  },
  emits: ["setTab"],
  template: "<div class=\"ha-setup-wizard__nav\">\n        <button class=\"ha-setup-wizard__nav_prev\" v-if=\"prev\" @click=\"$emit('setTab',prev)\">\n            <svg width=\"12\" height=\"8\" viewBox=\"0 0 12 8\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M12 3.33333H2.55333L4.94 0.94L4 0L0 4L4 8L4.94 7.06L2.55333 4.66667H12V3.33333Z\" fill=\"black\"/>\n            </svg>\n            <span>Back</span>\n        </button>\n\t\t<button class=\"ha-setup-wizard__nav_bepro\" v-if=\"bepro\" @click=\"$emit('setTab','buypro')\">\n\t\t\t<svg width=\"20\" height=\"16\" viewBox=\"0 0 20 16\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n\t\t\t\t<path d=\"M19.8347 5.42149C19.8347 6.21488 19.1736 6.87603 18.3802 6.87603C18.2479 6.87603 18.2479 6.87603 18.1157 6.87603L15.8678 12.9587H3.96694L1.71901 6.87603C1.58678 6.87603 1.58678 6.87603 1.45455 6.87603C0.661157 6.87603 0 6.21488 0 5.42149C0 4.6281 0.661157 3.96694 1.45455 3.96694C2.24793 3.96694 2.90909 4.6281 2.90909 5.42149C2.90909 5.68595 2.90909 5.81818 2.77686 6.08264L5.02479 7.40496C5.55372 7.66942 6.08264 7.53719 6.34711 7.00826L8.99174 2.64463C8.59504 2.38017 8.46281 1.98347 8.46281 1.45455C8.46281 0.661157 9.12397 0 9.91736 0C10.7107 0 11.3719 0.661157 11.3719 1.45455C11.3719 1.98347 11.1074 2.38017 10.843 2.64463L13.3554 7.00826C13.6198 7.53719 14.281 7.66942 14.6777 7.40496L16.9256 6.08264C16.7934 5.95041 16.7934 5.68595 16.7934 5.42149C16.7934 4.6281 17.4545 3.96694 18.2479 3.96694C19.0413 3.96694 19.8347 4.6281 19.8347 5.42149ZM16.9256 14.4132V15.4711C16.9256 15.7355 16.6612 16 16.3967 16H3.43802C3.17355 16 2.90909 15.7355 2.90909 15.4711V14.4132C2.90909 14.1488 3.17355 13.8843 3.43802 13.8843H16.3967C16.6612 13.8843 16.9256 14.1488 16.9256 14.4132Z\" fill=\"#FFC5C5\"/>\n\t\t\t</svg>\t\t\n\t\t\t<span>Be A Pro</span>\n\t\t</button>\n        <button class=\"ha-setup-wizard__nav_next\" v-if=\"next\" @click=\"$emit('setTab',next)\"><span>Next</span></button>\n        <button class=\"ha-setup-wizard__nav_done\" v-if=\"done\" @click=\"$emit('setTab','done')\"><span>Done</span></button>\n    </div>\n\t"
});
app.mount("#ha-setup-wizard");
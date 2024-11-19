"use strict";

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
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
      return _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
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
      return _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2() {
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
      return _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee3() {
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
      return _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee4() {
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
      return _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee5() {
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
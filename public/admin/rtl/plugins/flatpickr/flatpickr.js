/* flatpickr v4.6.2,, @license MIT */ ! function(e, t) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define(t) : (e = e || self).flatpickr = t()
}(this, function() {
    "use strict";
    var e = function() {
            return (e = Object.assign || function(e) {
                for (var t, n = 1, a = arguments.length; n < a; n++)
                    for (var i in t = arguments[n]) Object.prototype.hasOwnProperty.call(t, i) && (e[i] = t[i]);
                return e
            }).apply(this, arguments)
        },
        t = ["onChange", "onClose", "onDayCreate", "onDestroy", "onKeyDown", "onMonthChange", "onOpen", "onParseConfig", "onReady", "onValueUpdate", "onYearChange", "onPreCalendarPosition"],
        n = {
            _disable: [],
            _enable: [],
            allowInput: !1,
            altFormat: "F j, Y",
            altInput: !1,
            altInputClass: "form-control input",
            animate: "object" == typeof window && -1 === window.navigator.userAgent.indexOf("MSIE"),
            ariaDateFormat: "F j, Y",
            clickOpens: !0,
            closeOnSelect: !0,
            conjunction: ", ",
            dateFormat: "Y-m-d",
            defaultHour: 12,
            defaultMinute: 0,
            defaultSeconds: 0,
            disable: [],
            disableMobile: !1,
            enable: [],
            enableSeconds: !1,
            enableTime: !1,
            errorHandler: function(e) {
                return "undefined" != typeof console && console.warn(e)
            },
            getWeek: function(e) {
                var t = new Date(e.getTime());
                t.setHours(0, 0, 0, 0), t.setDate(t.getDate() + 3 - (t.getDay() + 6) % 7);
                var n = new Date(t.getFullYear(), 0, 4);
                return 1 + Math.round(((t.getTime() - n.getTime()) / 864e5 - 3 + (n.getDay() + 6) % 7) / 7)
            },
            hourIncrement: 1,
            ignoredFocusElements: [],
            inline: !1,
            locale: "default",
            minuteIncrement: 5,
            mode: "single",
            monthSelectorType: "dropdown",
            nextArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",
            noCalendar: !1,
            now: new Date,
            onChange: [],
            onClose: [],
            onDayCreate: [],
            onDestroy: [],
            onKeyDown: [],
            onMonthChange: [],
            onOpen: [],
            onParseConfig: [],
            onReady: [],
            onValueUpdate: [],
            onYearChange: [],
            onPreCalendarPosition: [],
            plugins: [],
            position: "auto",
            positionElement: void 0,
            prevArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",
            shorthandCurrentMonth: !1,
            showMonths: 1,
            static: !1,
            time_24hr: !1,
            weekNumbers: !1,
            wrap: !1
        },
        a = {
            weekdays: {
                shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
            months: {
                shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                var t = e % 100;
                if (t > 3 && t < 21) return "th";
                switch (t % 10) {
                    case 1:
                        return "st";
                    case 2:
                        return "nd";
                    case 3:
                        return "rd";
                    default:
                        return "th"
                }
            },
            rangeSeparator: " to ",
            weekAbbreviation: "Wk",
            scrollTitle: "Scroll to increment",
            toggleTitle: "Click to toggle",
            amPM: ["AM", "PM"],
            yearAriaLabel: "Year",
            hourAriaLabel: "Hour",
            minuteAriaLabel: "Minute",
            time_24hr: !1
        },
        i = function(e) {
            return ("0" + e).slice(-2)
        },
        o = function(e) {
            return !0 === e ? 1 : 0
        };

    function r(e, t, n) {
        var a;
        return void 0 === n && (n = !1),
            function() {
                var i = this,
                    o = arguments;
                null !== a && clearTimeout(a), a = window.setTimeout(function() {
                    a = null, n || e.apply(i, o)
                }, t), n && !a && e.apply(i, o)
            }
    }
    var l = function(e) {
        return e instanceof Array ? e : [e]
    };

    function c(e, t, n) {
        if (!0 === n) return e.classList.add(t);
        e.classList.remove(t)
    }

    function d(e, t, n) {
        var a = window.document.createElement(e);
        return t = t || "", n = n || "", a.className = t, void 0 !== n && (a.textContent = n), a
    }

    function s(e) {
        for (; e.firstChild;) e.removeChild(e.firstChild)
    }

    function u(e, t) {
        var n = d("div", "numInputWrapper"),
            a = d("input", "numInput " + e),
            i = d("span", "arrowUp"),
            o = d("span", "arrowDown");
        if (-1 === navigator.userAgent.indexOf("MSIE 9.0") ? a.type = "number" : (a.type = "text", a.pattern = "\\d*"), void 0 !== t)
            for (var r in t) a.setAttribute(r, t[r]);
        return n.appendChild(a), n.appendChild(i), n.appendChild(o), n
    }
    var f = function() {},
        m = function(e, t, n) {
            return n.months[t ? "shorthand" : "longhand"][e]
        },
        g = {
            D: f,
            F: function(e, t, n) {
                e.setMonth(n.months.longhand.indexOf(t))
            },
            G: function(e, t) {
                e.setHours(parseFloat(t))
            },
            H: function(e, t) {
                e.setHours(parseFloat(t))
            },
            J: function(e, t) {
                e.setDate(parseFloat(t))
            },
            K: function(e, t, n) {
                e.setHours(e.getHours() % 12 + 12 * o(new RegExp(n.amPM[1], "i").test(t)))
            },
            M: function(e, t, n) {
                e.setMonth(n.months.shorthand.indexOf(t))
            },
            S: function(e, t) {
                e.setSeconds(parseFloat(t))
            },
            U: function(e, t) {
                return new Date(1e3 * parseFloat(t))
            },
            W: function(e, t, n) {
                var a = parseInt(t),
                    i = new Date(e.getFullYear(), 0, 2 + 7 * (a - 1), 0, 0, 0, 0);
                return i.setDate(i.getDate() - i.getDay() + n.firstDayOfWeek), i
            },
            Y: function(e, t) {
                e.setFullYear(parseFloat(t))
            },
            Z: function(e, t) {
                return new Date(t)
            },
            d: function(e, t) {
                e.setDate(parseFloat(t))
            },
            h: function(e, t) {
                e.setHours(parseFloat(t))
            },
            i: function(e, t) {
                e.setMinutes(parseFloat(t))
            },
            j: function(e, t) {
                e.setDate(parseFloat(t))
            },
            l: f,
            m: function(e, t) {
                e.setMonth(parseFloat(t) - 1)
            },
            n: function(e, t) {
                e.setMonth(parseFloat(t) - 1)
            },
            s: function(e, t) {
                e.setSeconds(parseFloat(t))
            },
            u: function(e, t) {
                return new Date(parseFloat(t))
            },
            w: f,
            y: function(e, t) {
                e.setFullYear(2e3 + parseFloat(t))
            }
        },
        p = {
            D: "(\\w+)",
            F: "(\\w+)",
            G: "(\\d\\d|\\d)",
            H: "(\\d\\d|\\d)",
            J: "(\\d\\d|\\d)\\w+",
            K: "",
            M: "(\\w+)",
            S: "(\\d\\d|\\d)",
            U: "(.+)",
            W: "(\\d\\d|\\d)",
            Y: "(\\d{4})",
            Z: "(.+)",
            d: "(\\d\\d|\\d)",
            h: "(\\d\\d|\\d)",
            i: "(\\d\\d|\\d)",
            j: "(\\d\\d|\\d)",
            l: "(\\w+)",
            m: "(\\d\\d|\\d)",
            n: "(\\d\\d|\\d)",
            s: "(\\d\\d|\\d)",
            u: "(.+)",
            w: "(\\d\\d|\\d)",
            y: "(\\d{2})"
        },
        h = {
            Z: function(e) {
                return e.toISOString()
            },
            D: function(e, t, n) {
                return t.weekdays.shorthand[h.w(e, t, n)]
            },
            F: function(e, t, n) {
                return m(h.n(e, t, n) - 1, !1, t)
            },
            G: function(e, t, n) {
                return i(h.h(e, t, n))
            },
            H: function(e) {
                return i(e.getHours())
            },
            J: function(e, t) {
                return void 0 !== t.ordinal ? e.getDate() + t.ordinal(e.getDate()) : e.getDate()
            },
            K: function(e, t) {
                return t.amPM[o(e.getHours() > 11)]
            },
            M: function(e, t) {
                return m(e.getMonth(), !0, t)
            },
            S: function(e) {
                return i(e.getSeconds())
            },
            U: function(e) {
                return e.getTime() / 1e3
            },
            W: function(e, t, n) {
                return n.getWeek(e)
            },
            Y: function(e) {
                return e.getFullYear()
            },
            d: function(e) {
                return i(e.getDate())
            },
            h: function(e) {
                return e.getHours() % 12 ? e.getHours() % 12 : 12
            },
            i: function(e) {
                return i(e.getMinutes())
            },
            j: function(e) {
                return e.getDate()
            },
            l: function(e, t) {
                return t.weekdays.longhand[e.getDay()]
            },
            m: function(e) {
                return i(e.getMonth() + 1)
            },
            n: function(e) {
                return e.getMonth() + 1
            },
            s: function(e) {
                return e.getSeconds()
            },
            u: function(e) {
                return e.getTime()
            },
            w: function(e) {
                return e.getDay()
            },
            y: function(e) {
                return String(e.getFullYear()).substring(2)
            }
        },
        v = function(e) {
            var t = e.config,
                i = void 0 === t ? n : t,
                o = e.l10n,
                r = void 0 === o ? a : o;
            return function(e, t, n) {
                var a = n || r;
                return void 0 !== i.formatDate ? i.formatDate(e, t, a) : t.split("").map(function(t, n, o) {
                    return h[t] && "\\" !== o[n - 1] ? h[t](e, a, i) : "\\" !== t ? t : ""
                }).join("")
            }
        },
        D = function(e) {
            var t = e.config,
                i = void 0 === t ? n : t,
                o = e.l10n,
                r = void 0 === o ? a : o;
            return function(e, t, a, o) {
                if (0 === e || e) {
                    var l, c = o || r,
                        d = e;
                    if (e instanceof Date) l = new Date(e.getTime());
                    else if ("string" != typeof e && void 0 !== e.toFixed) l = new Date(e);
                    else if ("string" == typeof e) {
                        var s = t || (i || n).dateFormat,
                            u = String(e).trim();
                        if ("today" === u) l = new Date, a = !0;
                        else if (/Z$/.test(u) || /GMT$/.test(u)) l = new Date(e);
                        else if (i && i.parseDate) l = i.parseDate(e, s);
                        else {
                            l = i && i.noCalendar ? new Date((new Date).setHours(0, 0, 0, 0)) : new Date((new Date).getFullYear(), 0, 1, 0, 0, 0, 0);
                            for (var f = void 0, m = [], h = 0, v = 0, D = ""; h < s.length; h++) {
                                var w = s[h],
                                    b = "\\" === w,
                                    C = "\\" === s[h - 1] || b;
                                if (p[w] && !C) {
                                    D += p[w];
                                    var M = new RegExp(D).exec(e);
                                    M && (f = !0) && m["Y" !== w ? "push" : "unshift"]({
                                        fn: g[w],
                                        val: M[++v]
                                    })
                                } else b || (D += ".");
                                m.forEach(function(e) {
                                    var t = e.fn,
                                        n = e.val;
                                    return l = t(l, n, c) || l
                                })
                            }
                            l = f ? l : void 0
                        }
                    }
                    if (l instanceof Date && !isNaN(l.getTime())) return !0 === a && l.setHours(0, 0, 0, 0), l;
                    i.errorHandler(new Error("Invalid date provided: " + d))
                }
            }
        };

    function w(e, t, n) {
        return void 0 === n && (n = !0), !1 !== n ? new Date(e.getTime()).setHours(0, 0, 0, 0) - new Date(t.getTime()).setHours(0, 0, 0, 0) : e.getTime() - t.getTime()
    }
    var b = function(e, t, n) {
            return e > Math.min(t, n) && e < Math.max(t, n)
        },
        C = {
            DAY: 864e5
        };
    "function" != typeof Object.assign && (Object.assign = function(e) {
        for (var t = [], n = 1; n < arguments.length; n++) t[n - 1] = arguments[n];
        if (!e) throw TypeError("Cannot convert undefined or null to object");
        for (var a = function(t) {
                t && Object.keys(t).forEach(function(n) {
                    return e[n] = t[n]
                })
            }, i = 0, o = t; i < o.length; i++) {
            a(o[i])
        }
        return e
    });
    var M = 300;

    function y(f, g) {
        var h = {
            config: e({}, n, E.defaultConfig),
            l10n: a
        };

        function y(e) {
            return e.bind(h)
        }

        function x() {
            var e = h.config;
            !1 === e.weekNumbers && 1 === e.showMonths || !0 !== e.noCalendar && window.requestAnimationFrame(function() {
                if (void 0 !== h.calendarContainer && (h.calendarContainer.style.visibility = "hidden", h.calendarContainer.style.display = "block"), void 0 !== h.daysContainer) {
                    var t = (h.days.offsetWidth + 1) * e.showMonths;
                    h.daysContainer.style.width = t + "px", h.calendarContainer.style.width = t + (void 0 !== h.weekWrapper ? h.weekWrapper.offsetWidth : 0) + "px", h.calendarContainer.style.removeProperty("visibility"), h.calendarContainer.style.removeProperty("display")
                }
            })
        }

        function T(e) {
            0 === h.selectedDates.length && ie(), void 0 !== e && "blur" !== e.type && function(e) {
                e.preventDefault();
                var t = "keydown" === e.type,
                    n = e.target;
                void 0 !== h.amPM && e.target === h.amPM && (h.amPM.textContent = h.l10n.amPM[o(h.amPM.textContent === h.l10n.amPM[0])]);
                var a = parseFloat(n.getAttribute("min")),
                    r = parseFloat(n.getAttribute("max")),
                    l = parseFloat(n.getAttribute("step")),
                    c = parseInt(n.value, 10),
                    d = e.delta || (t ? 38 === e.which ? 1 : -1 : 0),
                    s = c + l * d;
                if (void 0 !== n.value && 2 === n.value.length) {
                    var u = n === h.hourElement,
                        f = n === h.minuteElement;
                    s < a ? (s = r + s + o(!u) + (o(u) && o(!h.amPM)), f && j(void 0, -1, h.hourElement)) : s > r && (s = n === h.hourElement ? s - r - o(!h.amPM) : a, f && j(void 0, 1, h.hourElement)), h.amPM && u && (1 === l ? s + c === 23 : Math.abs(s - c) > l) && (h.amPM.textContent = h.l10n.amPM[o(h.amPM.textContent === h.l10n.amPM[0])]), n.value = i(s)
                }
            }(e);
            var t = h._input.value;
            k(), we(), h._input.value !== t && h._debouncedChange()
        }

        function k() {
            if (void 0 !== h.hourElement && void 0 !== h.minuteElement) {
                var e, t, n = (parseInt(h.hourElement.value.slice(-2), 10) || 0) % 24,
                    a = (parseInt(h.minuteElement.value, 10) || 0) % 60,
                    i = void 0 !== h.secondElement ? (parseInt(h.secondElement.value, 10) || 0) % 60 : 0;
                void 0 !== h.amPM && (e = n, t = h.amPM.textContent, n = e % 12 + 12 * o(t === h.l10n.amPM[1]));
                var r = void 0 !== h.config.minTime || h.config.minDate && h.minDateHasTime && h.latestSelectedDateObj && 0 === w(h.latestSelectedDateObj, h.config.minDate, !0);
                if (void 0 !== h.config.maxTime || h.config.maxDate && h.maxDateHasTime && h.latestSelectedDateObj && 0 === w(h.latestSelectedDateObj, h.config.maxDate, !0)) {
                    var l = void 0 !== h.config.maxTime ? h.config.maxTime : h.config.maxDate;
                    (n = Math.min(n, l.getHours())) === l.getHours() && (a = Math.min(a, l.getMinutes())), a === l.getMinutes() && (i = Math.min(i, l.getSeconds()))
                }
                if (r) {
                    var c = void 0 !== h.config.minTime ? h.config.minTime : h.config.minDate;
                    (n = Math.max(n, c.getHours())) === c.getHours() && (a = Math.max(a, c.getMinutes())), a === c.getMinutes() && (i = Math.max(i, c.getSeconds()))
                }
                O(n, a, i)
            }
        }

        function I(e) {
            var t = e || h.latestSelectedDateObj;
            t && O(t.getHours(), t.getMinutes(), t.getSeconds())
        }

        function S() {
            var e = h.config.defaultHour,
                t = h.config.defaultMinute,
                n = h.config.defaultSeconds;
            if (void 0 !== h.config.minDate) {
                var a = h.config.minDate.getHours(),
                    i = h.config.minDate.getMinutes();
                (e = Math.max(e, a)) === a && (t = Math.max(i, t)), e === a && t === i && (n = h.config.minDate.getSeconds())
            }
            if (void 0 !== h.config.maxDate) {
                var o = h.config.maxDate.getHours(),
                    r = h.config.maxDate.getMinutes();
                (e = Math.min(e, o)) === o && (t = Math.min(r, t)), e === o && t === r && (n = h.config.maxDate.getSeconds())
            }
            O(e, t, n)
        }

        function O(e, t, n) {
            void 0 !== h.latestSelectedDateObj && h.latestSelectedDateObj.setHours(e % 24, t, n || 0, 0), h.hourElement && h.minuteElement && !h.isMobile && (h.hourElement.value = i(h.config.time_24hr ? e : (12 + e) % 12 + 12 * o(e % 12 == 0)), h.minuteElement.value = i(t), void 0 !== h.amPM && (h.amPM.textContent = h.l10n.amPM[o(e >= 12)]), void 0 !== h.secondElement && (h.secondElement.value = i(n)))
        }

        function _(e) {
            var t = parseInt(e.target.value) + (e.delta || 0);
            (t / 1e3 > 1 || "Enter" === e.key && !/[^\d]/.test(t.toString())) && Q(t)
        }

        function F(e, t, n, a) {
            return t instanceof Array ? t.forEach(function(t) {
                return F(e, t, n, a)
            }) : e instanceof Array ? e.forEach(function(e) {
                return F(e, t, n, a)
            }) : (e.addEventListener(t, n, a), void h._handlers.push({
                element: e,
                event: t,
                handler: n,
                options: a
            }))
        }

        function N(e) {
            return function(t) {
                1 === t.which && e(t)
            }
        }

        function Y() {
            ge("onChange")
        }

        function A(e, t) {
            var n = void 0 !== e ? h.parseDate(e) : h.latestSelectedDateObj || (h.config.minDate && h.config.minDate > h.now ? h.config.minDate : h.config.maxDate && h.config.maxDate < h.now ? h.config.maxDate : h.now),
                a = h.currentYear,
                i = h.currentMonth;
            try {
                void 0 !== n && (h.currentYear = n.getFullYear(), h.currentMonth = n.getMonth())
            } catch (e) {
                e.message = "Invalid date supplied: " + n, h.config.errorHandler(e)
            }
            t && h.currentYear !== a && (ge("onYearChange"), K()), !t || h.currentYear === a && h.currentMonth === i || ge("onMonthChange"), h.redraw()
        }

        function P(e) {
            ~e.target.className.indexOf("arrow") && j(e, e.target.classList.contains("arrowUp") ? 1 : -1)
        }

        function j(e, t, n) {
            var a = e && e.target,
                i = n || a && a.parentNode && a.parentNode.firstChild,
                o = pe("increment");
            o.delta = t, i && i.dispatchEvent(o)
        }

        function H(e, t, n, a) {
            var i = X(t, !0),
                o = d("span", "flatpickr-day " + e, t.getDate().toString());
            return o.dateObj = t, o.$i = a, o.setAttribute("aria-label", h.formatDate(t, h.config.ariaDateFormat)), -1 === e.indexOf("hidden") && 0 === w(t, h.now) && (h.todayDateElem = o, o.classList.add("today"), o.setAttribute("aria-current", "date")), i ? (o.tabIndex = -1, he(t) && (o.classList.add("selected"), h.selectedDateElem = o, "range" === h.config.mode && (c(o, "startRange", h.selectedDates[0] && 0 === w(t, h.selectedDates[0], !0)), c(o, "endRange", h.selectedDates[1] && 0 === w(t, h.selectedDates[1], !0)), "nextMonthDay" === e && o.classList.add("inRange")))) : o.classList.add("flatpickr-disabled"), "range" === h.config.mode && function(e) {
                return !("range" !== h.config.mode || h.selectedDates.length < 2) && w(e, h.selectedDates[0]) >= 0 && w(e, h.selectedDates[1]) <= 0
            }(t) && !he(t) && o.classList.add("inRange"), h.weekNumbers && 1 === h.config.showMonths && "prevMonthDay" !== e && n % 7 == 1 && h.weekNumbers.insertAdjacentHTML("beforeend", "<span class='flatpickr-day'>" + h.config.getWeek(t) + "</span>"), ge("onDayCreate", o), o
        }

        function L(e) {
            e.focus(), "range" === h.config.mode && ne(e)
        }

        function W(e) {
            for (var t = e > 0 ? 0 : h.config.showMonths - 1, n = e > 0 ? h.config.showMonths : -1, a = t; a != n; a += e)
                for (var i = h.daysContainer.children[a], o = e > 0 ? 0 : i.children.length - 1, r = e > 0 ? i.children.length : -1, l = o; l != r; l += e) {
                    var c = i.children[l];
                    if (-1 === c.className.indexOf("hidden") && X(c.dateObj)) return c
                }
        }

        function R(e, t) {
            var n = ee(document.activeElement || document.body),
                a = void 0 !== e ? e : n ? document.activeElement : void 0 !== h.selectedDateElem && ee(h.selectedDateElem) ? h.selectedDateElem : void 0 !== h.todayDateElem && ee(h.todayDateElem) ? h.todayDateElem : W(t > 0 ? 1 : -1);
            return void 0 === a ? h._input.focus() : n ? void
            function(e, t) {
                for (var n = -1 === e.className.indexOf("Month") ? e.dateObj.getMonth() : h.currentMonth, a = t > 0 ? h.config.showMonths : -1, i = t > 0 ? 1 : -1, o = n - h.currentMonth; o != a; o += i)
                    for (var r = h.daysContainer.children[o], l = n - h.currentMonth === o ? e.$i + t : t < 0 ? r.children.length - 1 : 0, c = r.children.length, d = l; d >= 0 && d < c && d != (t > 0 ? c : -1); d += i) {
                        var s = r.children[d];
                        if (-1 === s.className.indexOf("hidden") && X(s.dateObj) && Math.abs(e.$i - d) >= Math.abs(t)) return L(s)
                    }
                h.changeMonth(i), R(W(i), 0)
            }(a, t): L(a)
        }

        function B(e, t) {
            for (var n = (new Date(e, t, 1).getDay() - h.l10n.firstDayOfWeek + 7) % 7, a = h.utils.getDaysInMonth((t - 1 + 12) % 12), i = h.utils.getDaysInMonth(t), o = window.document.createDocumentFragment(), r = h.config.showMonths > 1, l = r ? "prevMonthDay hidden" : "prevMonthDay", c = r ? "nextMonthDay hidden" : "nextMonthDay", s = a + 1 - n, u = 0; s <= a; s++, u++) o.appendChild(H(l, new Date(e, t - 1, s), s, u));
            for (s = 1; s <= i; s++, u++) o.appendChild(H("", new Date(e, t, s), s, u));
            for (var f = i + 1; f <= 42 - n && (1 === h.config.showMonths || u % 7 != 0); f++, u++) o.appendChild(H(c, new Date(e, t + 1, f % i), f, u));
            var m = d("div", "dayContainer");
            return m.appendChild(o), m
        }

        function J() {
            if (void 0 !== h.daysContainer) {
                s(h.daysContainer), h.weekNumbers && s(h.weekNumbers);
                for (var e = document.createDocumentFragment(), t = 0; t < h.config.showMonths; t++) {
                    var n = new Date(h.currentYear, h.currentMonth, 1);
                    n.setMonth(h.currentMonth + t), e.appendChild(B(n.getFullYear(), n.getMonth()))
                }
                h.daysContainer.appendChild(e), h.days = h.daysContainer.firstChild, "range" === h.config.mode && 1 === h.selectedDates.length && ne()
            }
        }

        function K() {
            if (!(h.config.showMonths > 1 || "dropdown" !== h.config.monthSelectorType)) {
                var e = function(e) {
                    return !(void 0 !== h.config.minDate && h.currentYear === h.config.minDate.getFullYear() && e < h.config.minDate.getMonth()) && !(void 0 !== h.config.maxDate && h.currentYear === h.config.maxDate.getFullYear() && e > h.config.maxDate.getMonth())
                };
                h.monthsDropdownContainer.tabIndex = -1, h.monthsDropdownContainer.innerHTML = "";
                for (var t = 0; t < 12; t++)
                    if (e(t)) {
                        var n = d("option", "flatpickr-monthDropdown-month");
                        n.value = new Date(h.currentYear, t).getMonth().toString(), n.textContent = m(t, h.config.shorthandCurrentMonth, h.l10n), n.tabIndex = -1, h.currentMonth === t && (n.selected = !0), h.monthsDropdownContainer.appendChild(n)
                    }
            }
        }

        function U() {
            var e, t = d("div", "flatpickr-month"),
                n = window.document.createDocumentFragment();
            h.config.showMonths > 1 || "static" === h.config.monthSelectorType ? e = d("span", "cur-month") : (h.monthsDropdownContainer = d("select", "flatpickr-monthDropdown-months"), F(h.monthsDropdownContainer, "change", function(e) {
                var t = e.target,
                    n = parseInt(t.value, 10);
                h.changeMonth(n - h.currentMonth), ge("onMonthChange")
            }), K(), e = h.monthsDropdownContainer);
            var a = u("cur-year", {
                    tabindex: "-1"
                }),
                i = a.getElementsByTagName("input")[0];
            i.setAttribute("aria-label", h.l10n.yearAriaLabel), h.config.minDate && i.setAttribute("min", h.config.minDate.getFullYear().toString()), h.config.maxDate && (i.setAttribute("max", h.config.maxDate.getFullYear().toString()), i.disabled = !!h.config.minDate && h.config.minDate.getFullYear() === h.config.maxDate.getFullYear());
            var o = d("div", "flatpickr-current-month");
            return o.appendChild(e), o.appendChild(a), n.appendChild(o), t.appendChild(n), {
                container: t,
                yearElement: i,
                monthElement: e
            }
        }

        function q() {
            s(h.monthNav), h.monthNav.appendChild(h.prevMonthNav), h.config.showMonths && (h.yearElements = [], h.monthElements = []);
            for (var e = h.config.showMonths; e--;) {
                var t = U();
                h.yearElements.push(t.yearElement), h.monthElements.push(t.monthElement), h.monthNav.appendChild(t.container)
            }
            h.monthNav.appendChild(h.nextMonthNav)
        }

        function $() {
            h.weekdayContainer ? s(h.weekdayContainer) : h.weekdayContainer = d("div", "flatpickr-weekdays");
            for (var e = h.config.showMonths; e--;) {
                var t = d("div", "flatpickr-weekdaycontainer");
                h.weekdayContainer.appendChild(t)
            }
            return z(), h.weekdayContainer
        }

        function z() {
            var e = h.l10n.firstDayOfWeek,
                t = h.l10n.weekdays.shorthand.slice();
            e > 0 && e < t.length && (t = t.splice(e, t.length).concat(t.splice(0, e)));
            for (var n = h.config.showMonths; n--;) h.weekdayContainer.children[n].innerHTML = "\n      <span class='flatpickr-weekday'>\n        " + t.join("</span><span class='flatpickr-weekday'>") + "\n      </span>\n      "
        }

        function G(e, t) {
            void 0 === t && (t = !0);
            var n = t ? e : e - h.currentMonth;
            n < 0 && !0 === h._hidePrevMonthArrow || n > 0 && !0 === h._hideNextMonthArrow || (h.currentMonth += n, (h.currentMonth < 0 || h.currentMonth > 11) && (h.currentYear += h.currentMonth > 11 ? 1 : -1, h.currentMonth = (h.currentMonth + 12) % 12, ge("onYearChange"), K()), J(), ge("onMonthChange"), ve())
        }

        function V(e) {
            return !(!h.config.appendTo || !h.config.appendTo.contains(e)) || h.calendarContainer.contains(e)
        }

        function Z(e) {
            if (h.isOpen && !h.config.inline) {
                var t = "function" == typeof(r = e).composedPath ? r.composedPath()[0] : r.target,
                    n = V(t),
                    a = t === h.input || t === h.altInput || h.element.contains(t) || e.path && e.path.indexOf && (~e.path.indexOf(h.input) || ~e.path.indexOf(h.altInput)),
                    i = "blur" === e.type ? a && e.relatedTarget && !V(e.relatedTarget) : !a && !n && !V(e.relatedTarget),
                    o = !h.config.ignoredFocusElements.some(function(e) {
                        return e.contains(t)
                    });
                i && o && (h.close(), "range" === h.config.mode && 1 === h.selectedDates.length && (h.clear(!1), h.redraw()))
            }
            var r
        }

        function Q(e) {
            if (!(!e || h.config.minDate && e < h.config.minDate.getFullYear() || h.config.maxDate && e > h.config.maxDate.getFullYear())) {
                var t = e,
                    n = h.currentYear !== t;
                h.currentYear = t || h.currentYear, h.config.maxDate && h.currentYear === h.config.maxDate.getFullYear() ? h.currentMonth = Math.min(h.config.maxDate.getMonth(), h.currentMonth) : h.config.minDate && h.currentYear === h.config.minDate.getFullYear() && (h.currentMonth = Math.max(h.config.minDate.getMonth(), h.currentMonth)), n && (h.redraw(), ge("onYearChange"), K())
            }
        }

        function X(e, t) {
            void 0 === t && (t = !0);
            var n = h.parseDate(e, void 0, t);
            if (h.config.minDate && n && w(n, h.config.minDate, void 0 !== t ? t : !h.minDateHasTime) < 0 || h.config.maxDate && n && w(n, h.config.maxDate, void 0 !== t ? t : !h.maxDateHasTime) > 0) return !1;
            if (0 === h.config.enable.length && 0 === h.config.disable.length) return !0;
            if (void 0 === n) return !1;
            for (var a = h.config.enable.length > 0, i = a ? h.config.enable : h.config.disable, o = 0, r = void 0; o < i.length; o++) {
                if ("function" == typeof(r = i[o]) && r(n)) return a;
                if (r instanceof Date && void 0 !== n && r.getTime() === n.getTime()) return a;
                if ("string" == typeof r && void 0 !== n) {
                    var l = h.parseDate(r, void 0, !0);
                    return l && l.getTime() === n.getTime() ? a : !a
                }
                if ("object" == typeof r && void 0 !== n && r.from && r.to && n.getTime() >= r.from.getTime() && n.getTime() <= r.to.getTime()) return a
            }
            return !a
        }

        function ee(e) {
            return void 0 !== h.daysContainer && (-1 === e.className.indexOf("hidden") && h.daysContainer.contains(e))
        }

        function te(e) {
            var t = e.target === h._input,
                n = h.config.allowInput,
                a = h.isOpen && (!n || !t),
                i = h.config.inline && t && !n;
            if (13 === e.keyCode && t) {
                if (n) return h.setDate(h._input.value, !0, e.target === h.altInput ? h.config.altFormat : h.config.dateFormat), e.target.blur();
                h.open()
            } else if (V(e.target) || a || i) {
                var o = !!h.timeContainer && h.timeContainer.contains(e.target);
                switch (e.keyCode) {
                    case 13:
                        o ? (e.preventDefault(), T(), de()) : se(e);
                        break;
                    case 27:
                        e.preventDefault(), de();
                        break;
                    case 8:
                    case 46:
                        t && !h.config.allowInput && (e.preventDefault(), h.clear());
                        break;
                    case 37:
                    case 39:
                        if (o || t) h.hourElement && h.hourElement.focus();
                        else if (e.preventDefault(), void 0 !== h.daysContainer && (!1 === n || document.activeElement && ee(document.activeElement))) {
                            var r = 39 === e.keyCode ? 1 : -1;
                            e.ctrlKey ? (e.stopPropagation(), G(r), R(W(1), 0)) : R(void 0, r)
                        }
                        break;
                    case 38:
                    case 40:
                        e.preventDefault();
                        var l = 40 === e.keyCode ? 1 : -1;
                        h.daysContainer && void 0 !== e.target.$i || e.target === h.input ? e.ctrlKey ? (e.stopPropagation(), Q(h.currentYear - l), R(W(1), 0)) : o || R(void 0, 7 * l) : e.target === h.currentYearElement ? Q(h.currentYear - l) : h.config.enableTime && (!o && h.hourElement && h.hourElement.focus(), T(e), h._debouncedChange());
                        break;
                    case 9:
                        if (o) {
                            var c = [h.hourElement, h.minuteElement, h.secondElement, h.amPM].concat(h.pluginElements).filter(function(e) {
                                    return e
                                }),
                                d = c.indexOf(e.target);
                            if (-1 !== d) {
                                var s = c[d + (e.shiftKey ? -1 : 1)];
                                e.preventDefault(), (s || h._input).focus()
                            }
                        } else !h.config.noCalendar && h.daysContainer && h.daysContainer.contains(e.target) && e.shiftKey && (e.preventDefault(), h._input.focus())
                }
            }
            if (void 0 !== h.amPM && e.target === h.amPM) switch (e.key) {
                case h.l10n.amPM[0].charAt(0):
                case h.l10n.amPM[0].charAt(0).toLowerCase():
                    h.amPM.textContent = h.l10n.amPM[0], k(), we();
                    break;
                case h.l10n.amPM[1].charAt(0):
                case h.l10n.amPM[1].charAt(0).toLowerCase():
                    h.amPM.textContent = h.l10n.amPM[1], k(), we()
            }(t || V(e.target)) && ge("onKeyDown", e)
        }

        function ne(e) {
            if (1 === h.selectedDates.length && (!e || e.classList.contains("flatpickr-day") && !e.classList.contains("flatpickr-disabled"))) {
                for (var t = e ? e.dateObj.getTime() : h.days.firstElementChild.dateObj.getTime(), n = h.parseDate(h.selectedDates[0], void 0, !0).getTime(), a = Math.min(t, h.selectedDates[0].getTime()), i = Math.max(t, h.selectedDates[0].getTime()), o = !1, r = 0, l = 0, c = a; c < i; c += C.DAY) X(new Date(c), !0) || (o = o || c > a && c < i, c < n && (!r || c > r) ? r = c : c > n && (!l || c < l) && (l = c));
                for (var d = 0; d < h.config.showMonths; d++)
                    for (var s = h.daysContainer.children[d], u = function(a, i) {
                            var c = s.children[a],
                                d = c.dateObj.getTime(),
                                u = r > 0 && d < r || l > 0 && d > l;
                            return u ? (c.classList.add("notAllowed"), ["inRange", "startRange", "endRange"].forEach(function(e) {
                                c.classList.remove(e)
                            }), "continue") : o && !u ? "continue" : (["startRange", "inRange", "endRange", "notAllowed"].forEach(function(e) {
                                c.classList.remove(e)
                            }), void(void 0 !== e && (e.classList.add(t <= h.selectedDates[0].getTime() ? "startRange" : "endRange"), n < t && d === n ? c.classList.add("startRange") : n > t && d === n && c.classList.add("endRange"), d >= r && (0 === l || d <= l) && b(d, n, t) && c.classList.add("inRange"))))
                        }, f = 0, m = s.children.length; f < m; f++) u(f)
            }
        }

        function ae() {
            !h.isOpen || h.config.static || h.config.inline || le()
        }

        function ie() {
            h.setDate(void 0 !== h.config.minDate ? new Date(h.config.minDate.getTime()) : new Date, !0), S(), we()
        }

        function oe(e) {
            return function(t) {
                var n = h.config["_" + e + "Date"] = h.parseDate(t, h.config.dateFormat),
                    a = h.config["_" + ("min" === e ? "max" : "min") + "Date"];
                void 0 !== n && (h["min" === e ? "minDateHasTime" : "maxDateHasTime"] = n.getHours() > 0 || n.getMinutes() > 0 || n.getSeconds() > 0), h.selectedDates && (h.selectedDates = h.selectedDates.filter(function(e) {
                    return X(e)
                }), h.selectedDates.length || "min" !== e || I(n), we()), h.daysContainer && (ce(), void 0 !== n ? h.currentYearElement[e] = n.getFullYear().toString() : h.currentYearElement.removeAttribute(e), h.currentYearElement.disabled = !!a && void 0 !== n && a.getFullYear() === n.getFullYear())
            }
        }

        function re() {
            "object" != typeof h.config.locale && void 0 === E.l10ns[h.config.locale] && h.config.errorHandler(new Error("flatpickr: invalid locale " + h.config.locale)), h.l10n = e({}, E.l10ns.default, "object" == typeof h.config.locale ? h.config.locale : "default" !== h.config.locale ? E.l10ns[h.config.locale] : void 0), p.K = "(" + h.l10n.amPM[0] + "|" + h.l10n.amPM[1] + "|" + h.l10n.amPM[0].toLowerCase() + "|" + h.l10n.amPM[1].toLowerCase() + ")", void 0 === e({}, g, JSON.parse(JSON.stringify(f.dataset || {}))).time_24hr && void 0 === E.defaultConfig.time_24hr && (h.config.time_24hr = h.l10n.time_24hr), h.formatDate = v(h), h.parseDate = D({
                config: h.config,
                l10n: h.l10n
            })
        }

        function le(e) {
            if (void 0 !== h.calendarContainer) {
                ge("onPreCalendarPosition");
                var t = e || h._positionElement,
                    n = Array.prototype.reduce.call(h.calendarContainer.children, function(e, t) {
                        return e + t.offsetHeight
                    }, 0),
                    a = h.calendarContainer.offsetWidth,
                    i = h.config.position.split(" "),
                    o = i[0],
                    r = i.length > 1 ? i[1] : null,
                    l = t.getBoundingClientRect(),
                    d = window.innerHeight - l.bottom,
                    s = "above" === o || "below" !== o && d < n && l.top > n,
                    u = window.pageYOffset + l.top + (s ? -n - 2 : t.offsetHeight + 2);
                if (c(h.calendarContainer, "arrowTop", !s), c(h.calendarContainer, "arrowBottom", s), !h.config.inline) {
                    var f = window.pageXOffset + l.right - (null != r && "center" === r ? (a - l.width) / 2 : 0),
                        m = window.document.body.offsetWidth - l.right,
                        g = f + a > window.document.body.offsetWidth,
                        p = m + a > window.document.body.offsetWidth;
                    if (c(h.calendarContainer, "rightMost", g), !h.config.static)
                        if (h.calendarContainer.style.top = u + "px", g)
                            if (p) {
                                var v = document.styleSheets[0];
                                if (void 0 === v) return;
                                var D = window.document.body.offsetWidth,
                                    w = Math.max(0, D / 2 - a / 2),
                                    b = v.cssRules.length,
                                    C = "{left:" + l.left + "px;right:auto;}";
                                c(h.calendarContainer, "rightMost", !1), c(h.calendarContainer, "centerMost", !0), v.insertRule(".flatpickr-calendar.centerMost:before,.flatpickr-calendar.centerMost:after" + C, b), h.calendarContainer.style.left = w + "px", h.calendarContainer.style.right = "auto"
                            } else h.calendarContainer.style.left = "auto", h.calendarContainer.style.right = m + "px";
                    else h.calendarContainer.style.left = f + "px", h.calendarContainer.style.right = "auto"
                }
            }
        }

        function ce() {
            h.config.noCalendar || h.isMobile || (ve(), J())
        }

        function de() {
            h._input.focus(), -1 !== window.navigator.userAgent.indexOf("MSIE") || void 0 !== navigator.msMaxTouchPoints ? setTimeout(h.close, 0) : h.close()
        }

        function se(e) {
            e.preventDefault(), e.stopPropagation();
            var t = function e(t, n) {
                return n(t) ? t : t.parentNode ? e(t.parentNode, n) : void 0
            }(e.target, function(e) {
                return e.classList && e.classList.contains("flatpickr-day") && !e.classList.contains("flatpickr-disabled") && !e.classList.contains("notAllowed")
            });
            if (void 0 !== t) {
                var n = t,
                    a = h.latestSelectedDateObj = new Date(n.dateObj.getTime()),
                    i = (a.getMonth() < h.currentMonth || a.getMonth() > h.currentMonth + h.config.showMonths - 1) && "range" !== h.config.mode;
                if (h.selectedDateElem = n, "single" === h.config.mode) h.selectedDates = [a];
                else if ("multiple" === h.config.mode) {
                    var o = he(a);
                    o ? h.selectedDates.splice(parseInt(o), 1) : h.selectedDates.push(a)
                } else "range" === h.config.mode && (2 === h.selectedDates.length && h.clear(!1, !1), h.latestSelectedDateObj = a, h.selectedDates.push(a), 0 !== w(a, h.selectedDates[0], !0) && h.selectedDates.sort(function(e, t) {
                    return e.getTime() - t.getTime()
                }));
                if (k(), i) {
                    var r = h.currentYear !== a.getFullYear();
                    h.currentYear = a.getFullYear(), h.currentMonth = a.getMonth(), r && (ge("onYearChange"), K()), ge("onMonthChange")
                }
                if (ve(), J(), we(), h.config.enableTime && setTimeout(function() {
                        return h.showTimeInput = !0
                    }, 50), i || "range" === h.config.mode || 1 !== h.config.showMonths ? void 0 !== h.selectedDateElem && void 0 === h.hourElement && h.selectedDateElem && h.selectedDateElem.focus() : L(n), void 0 !== h.hourElement && void 0 !== h.hourElement && h.hourElement.focus(), h.config.closeOnSelect) {
                    var l = "single" === h.config.mode && !h.config.enableTime,
                        c = "range" === h.config.mode && 2 === h.selectedDates.length && !h.config.enableTime;
                    (l || c) && de()
                }
                Y()
            }
        }
        h.parseDate = D({
            config: h.config,
            l10n: h.l10n
        }), h._handlers = [], h.pluginElements = [], h.loadedPlugins = [], h._bind = F, h._setHoursFromDate = I, h._positionCalendar = le, h.changeMonth = G, h.changeYear = Q, h.clear = function(e, t) {
            void 0 === e && (e = !0);
            void 0 === t && (t = !0);
            h.input.value = "", void 0 !== h.altInput && (h.altInput.value = "");
            void 0 !== h.mobileInput && (h.mobileInput.value = "");
            h.selectedDates = [], h.latestSelectedDateObj = void 0, !0 === t && (h.currentYear = h._initialDate.getFullYear(), h.currentMonth = h._initialDate.getMonth());
            h.showTimeInput = !1, !0 === h.config.enableTime && S();
            h.redraw(), e && ge("onChange")
        }, h.close = function() {
            h.isOpen = !1, h.isMobile || (void 0 !== h.calendarContainer && h.calendarContainer.classList.remove("open"), void 0 !== h._input && h._input.classList.remove("active"));
            ge("onClose")
        }, h._createElement = d, h.destroy = function() {
            void 0 !== h.config && ge("onDestroy");
            for (var e = h._handlers.length; e--;) {
                var t = h._handlers[e];
                t.element.removeEventListener(t.event, t.handler, t.options)
            }
            if (h._handlers = [], h.mobileInput) h.mobileInput.parentNode && h.mobileInput.parentNode.removeChild(h.mobileInput), h.mobileInput = void 0;
            else if (h.calendarContainer && h.calendarContainer.parentNode)
                if (h.config.static && h.calendarContainer.parentNode) {
                    var n = h.calendarContainer.parentNode;
                    if (n.lastChild && n.removeChild(n.lastChild), n.parentNode) {
                        for (; n.firstChild;) n.parentNode.insertBefore(n.firstChild, n);
                        n.parentNode.removeChild(n)
                    }
                } else h.calendarContainer.parentNode.removeChild(h.calendarContainer);
            h.altInput && (h.input.type = "text", h.altInput.parentNode && h.altInput.parentNode.removeChild(h.altInput), delete h.altInput);
            h.input && (h.input.type = h.input._type, h.input.classList.remove("flatpickr-input"), h.input.removeAttribute("readonly"), h.input.value = "");
            ["_showTimeInput", "latestSelectedDateObj", "_hideNextMonthArrow", "_hidePrevMonthArrow", "__hideNextMonthArrow", "__hidePrevMonthArrow", "isMobile", "isOpen", "selectedDateElem", "minDateHasTime", "maxDateHasTime", "days", "daysContainer", "_input", "_positionElement", "innerContainer", "rContainer", "monthNav", "todayDateElem", "calendarContainer", "weekdayContainer", "prevMonthNav", "nextMonthNav", "monthsDropdownContainer", "currentMonthElement", "currentYearElement", "navigationCurrentMonth", "selectedDateElem", "config"].forEach(function(e) {
                try {
                    delete h[e]
                } catch (e) {}
            })
        }, h.isEnabled = X, h.jumpToDate = A, h.open = function(e, t) {
            void 0 === t && (t = h._positionElement);
            if (!0 === h.isMobile) return e && (e.preventDefault(), e.target && e.target.blur()), void 0 !== h.mobileInput && (h.mobileInput.focus(), h.mobileInput.click()), void ge("onOpen");
            if (h._input.disabled || h.config.inline) return;
            var n = h.isOpen;
            h.isOpen = !0, n || (h.calendarContainer.classList.add("open"), h._input.classList.add("active"), ge("onOpen"), le(t));
            !0 === h.config.enableTime && !0 === h.config.noCalendar && (0 === h.selectedDates.length && ie(), !1 !== h.config.allowInput || void 0 !== e && h.timeContainer.contains(e.relatedTarget) || setTimeout(function() {
                return h.hourElement.select()
            }, 50))
        }, h.redraw = ce, h.set = function(e, n) {
            if (null !== e && "object" == typeof e)
                for (var a in Object.assign(h.config, e), e) void 0 !== ue[a] && ue[a].forEach(function(e) {
                    return e()
                });
            else h.config[e] = n, void 0 !== ue[e] ? ue[e].forEach(function(e) {
                return e()
            }) : t.indexOf(e) > -1 && (h.config[e] = l(n));
            h.redraw(), we(!1)
        }, h.setDate = function(e, t, n) {
            void 0 === t && (t = !1);
            void 0 === n && (n = h.config.dateFormat);
            if (0 !== e && !e || e instanceof Array && 0 === e.length) return h.clear(t);
            fe(e, n), h.showTimeInput = h.selectedDates.length > 0, h.latestSelectedDateObj = h.selectedDates[h.selectedDates.length - 1], h.redraw(), A(), I(), 0 === h.selectedDates.length && h.clear(!1);
            we(t), t && ge("onChange")
        }, h.toggle = function(e) {
            if (!0 === h.isOpen) return h.close();
            h.open(e)
        };
        var ue = {
            locale: [re, z],
            showMonths: [q, x, $],
            minDate: [A],
            maxDate: [A]
        };

        function fe(e, t) {
            var n = [];
            if (e instanceof Array) n = e.map(function(e) {
                return h.parseDate(e, t)
            });
            else if (e instanceof Date || "number" == typeof e) n = [h.parseDate(e, t)];
            else if ("string" == typeof e) switch (h.config.mode) {
                case "single":
                case "time":
                    n = [h.parseDate(e, t)];
                    break;
                case "multiple":
                    n = e.split(h.config.conjunction).map(function(e) {
                        return h.parseDate(e, t)
                    });
                    break;
                case "range":
                    n = e.split(h.l10n.rangeSeparator).map(function(e) {
                        return h.parseDate(e, t)
                    })
            } else h.config.errorHandler(new Error("Invalid date supplied: " + JSON.stringify(e)));
            h.selectedDates = n.filter(function(e) {
                return e instanceof Date && X(e, !1)
            }), "range" === h.config.mode && h.selectedDates.sort(function(e, t) {
                return e.getTime() - t.getTime()
            })
        }

        function me(e) {
            return e.slice().map(function(e) {
                return "string" == typeof e || "number" == typeof e || e instanceof Date ? h.parseDate(e, void 0, !0) : e && "object" == typeof e && e.from && e.to ? {
                    from: h.parseDate(e.from, void 0),
                    to: h.parseDate(e.to, void 0)
                } : e
            }).filter(function(e) {
                return e
            })
        }

        function ge(e, t) {
            if (void 0 !== h.config) {
                var n = h.config[e];
                if (void 0 !== n && n.length > 0)
                    for (var a = 0; n[a] && a < n.length; a++) n[a](h.selectedDates, h.input.value, h, t);
                "onChange" === e && (h.input.dispatchEvent(pe("change")), h.input.dispatchEvent(pe("input")))
            }
        }

        function pe(e) {
            var t = document.createEvent("Event");
            return t.initEvent(e, !0, !0), t
        }

        function he(e) {
            for (var t = 0; t < h.selectedDates.length; t++)
                if (0 === w(h.selectedDates[t], e)) return "" + t;
            return !1
        }

        function ve() {
            h.config.noCalendar || h.isMobile || !h.monthNav || (h.yearElements.forEach(function(e, t) {
                var n = new Date(h.currentYear, h.currentMonth, 1);
                n.setMonth(h.currentMonth + t), h.config.showMonths > 1 || "static" === h.config.monthSelectorType ? h.monthElements[t].textContent = m(n.getMonth(), h.config.shorthandCurrentMonth, h.l10n) + " " : h.monthsDropdownContainer.value = n.getMonth().toString(), e.value = n.getFullYear().toString()
            }), h._hidePrevMonthArrow = void 0 !== h.config.minDate && (h.currentYear === h.config.minDate.getFullYear() ? h.currentMonth <= h.config.minDate.getMonth() : h.currentYear < h.config.minDate.getFullYear()), h._hideNextMonthArrow = void 0 !== h.config.maxDate && (h.currentYear === h.config.maxDate.getFullYear() ? h.currentMonth + 1 > h.config.maxDate.getMonth() : h.currentYear > h.config.maxDate.getFullYear()))
        }

        function De(e) {
            return h.selectedDates.map(function(t) {
                return h.formatDate(t, e)
            }).filter(function(e, t, n) {
                return "range" !== h.config.mode || h.config.enableTime || n.indexOf(e) === t
            }).join("range" !== h.config.mode ? h.config.conjunction : h.l10n.rangeSeparator)
        }

        function we(e) {
            void 0 === e && (e = !0), void 0 !== h.mobileInput && h.mobileFormatStr && (h.mobileInput.value = void 0 !== h.latestSelectedDateObj ? h.formatDate(h.latestSelectedDateObj, h.mobileFormatStr) : ""), h.input.value = De(h.config.dateFormat), void 0 !== h.altInput && (h.altInput.value = De(h.config.altFormat)), !1 !== e && ge("onValueUpdate")
        }

        function be(e) {
            var t = h.prevMonthNav.contains(e.target),
                n = h.nextMonthNav.contains(e.target);
            t || n ? G(t ? -1 : 1) : h.yearElements.indexOf(e.target) >= 0 ? e.target.select() : e.target.classList.contains("arrowUp") ? h.changeYear(h.currentYear + 1) : e.target.classList.contains("arrowDown") && h.changeYear(h.currentYear - 1)
        }
        return function() {
            h.element = h.input = f, h.isOpen = !1,
                function() {
                    var a = ["wrap", "weekNumbers", "allowInput", "clickOpens", "time_24hr", "enableTime", "noCalendar", "altInput", "shorthandCurrentMonth", "inline", "static", "enableSeconds", "disableMobile"],
                        i = e({}, g, JSON.parse(JSON.stringify(f.dataset || {}))),
                        o = {};
                    h.config.parseDate = i.parseDate, h.config.formatDate = i.formatDate, Object.defineProperty(h.config, "enable", {
                        get: function() {
                            return h.config._enable
                        },
                        set: function(e) {
                            h.config._enable = me(e)
                        }
                    }), Object.defineProperty(h.config, "disable", {
                        get: function() {
                            return h.config._disable
                        },
                        set: function(e) {
                            h.config._disable = me(e)
                        }
                    });
                    var r = "time" === i.mode;
                    if (!i.dateFormat && (i.enableTime || r)) {
                        var c = E.defaultConfig.dateFormat || n.dateFormat;
                        o.dateFormat = i.noCalendar || r ? "H:i" + (i.enableSeconds ? ":S" : "") : c + " H:i" + (i.enableSeconds ? ":S" : "")
                    }
                    if (i.altInput && (i.enableTime || r) && !i.altFormat) {
                        var d = E.defaultConfig.altFormat || n.altFormat;
                        o.altFormat = i.noCalendar || r ? "h:i" + (i.enableSeconds ? ":S K" : " K") : d + " h:i" + (i.enableSeconds ? ":S" : "") + " K"
                    }
                    i.altInputClass || (h.config.altInputClass = h.input.className + " " + h.config.altInputClass), Object.defineProperty(h.config, "minDate", {
                        get: function() {
                            return h.config._minDate
                        },
                        set: oe("min")
                    }), Object.defineProperty(h.config, "maxDate", {
                        get: function() {
                            return h.config._maxDate
                        },
                        set: oe("max")
                    });
                    var s = function(e) {
                        return function(t) {
                            h.config["min" === e ? "_minTime" : "_maxTime"] = h.parseDate(t, "H:i")
                        }
                    };
                    Object.defineProperty(h.config, "minTime", {
                        get: function() {
                            return h.config._minTime
                        },
                        set: s("min")
                    }), Object.defineProperty(h.config, "maxTime", {
                        get: function() {
                            return h.config._maxTime
                        },
                        set: s("max")
                    }), "time" === i.mode && (h.config.noCalendar = !0, h.config.enableTime = !0), Object.assign(h.config, o, i);
                    for (var u = 0; u < a.length; u++) h.config[a[u]] = !0 === h.config[a[u]] || "true" === h.config[a[u]];
                    t.filter(function(e) {
                        return void 0 !== h.config[e]
                    }).forEach(function(e) {
                        h.config[e] = l(h.config[e] || []).map(y)
                    }), h.isMobile = !h.config.disableMobile && !h.config.inline && "single" === h.config.mode && !h.config.disable.length && !h.config.enable.length && !h.config.weekNumbers && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    for (var u = 0; u < h.config.plugins.length; u++) {
                        var m = h.config.plugins[u](h) || {};
                        for (var p in m) t.indexOf(p) > -1 ? h.config[p] = l(m[p]).map(y).concat(h.config[p]) : void 0 === i[p] && (h.config[p] = m[p])
                    }
                    ge("onParseConfig")
                }(), re(), h.input = h.config.wrap ? f.querySelector("[data-input]") : f, h.input ? (h.input._type = h.input.type, h.input.type = "text", h.input.classList.add("flatpickr-input"), h._input = h.input, h.config.altInput && (h.altInput = d(h.input.nodeName, h.config.altInputClass), h._input = h.altInput, h.altInput.placeholder = h.input.placeholder, h.altInput.disabled = h.input.disabled, h.altInput.required = h.input.required, h.altInput.tabIndex = h.input.tabIndex, h.altInput.type = "text", h.input.setAttribute("type", "hidden"), !h.config.static && h.input.parentNode && h.input.parentNode.insertBefore(h.altInput, h.input.nextSibling)), h.config.allowInput || h._input.setAttribute("readonly", "readonly"), h._positionElement = h.config.positionElement || h._input) : h.config.errorHandler(new Error("Invalid input element specified")),
                function() {
                    h.selectedDates = [], h.now = h.parseDate(h.config.now) || new Date;
                    var e = h.config.defaultDate || ("INPUT" !== h.input.nodeName && "TEXTAREA" !== h.input.nodeName || !h.input.placeholder || h.input.value !== h.input.placeholder ? h.input.value : null);
                    e && fe(e, h.config.dateFormat), h._initialDate = h.selectedDates.length > 0 ? h.selectedDates[0] : h.config.minDate && h.config.minDate.getTime() > h.now.getTime() ? h.config.minDate : h.config.maxDate && h.config.maxDate.getTime() < h.now.getTime() ? h.config.maxDate : h.now, h.currentYear = h._initialDate.getFullYear(), h.currentMonth = h._initialDate.getMonth(), h.selectedDates.length > 0 && (h.latestSelectedDateObj = h.selectedDates[0]), void 0 !== h.config.minTime && (h.config.minTime = h.parseDate(h.config.minTime, "H:i")), void 0 !== h.config.maxTime && (h.config.maxTime = h.parseDate(h.config.maxTime, "H:i")), h.minDateHasTime = !!h.config.minDate && (h.config.minDate.getHours() > 0 || h.config.minDate.getMinutes() > 0 || h.config.minDate.getSeconds() > 0), h.maxDateHasTime = !!h.config.maxDate && (h.config.maxDate.getHours() > 0 || h.config.maxDate.getMinutes() > 0 || h.config.maxDate.getSeconds() > 0), Object.defineProperty(h, "showTimeInput", {
                        get: function() {
                            return h._showTimeInput
                        },
                        set: function(e) {
                            h._showTimeInput = e, h.calendarContainer && c(h.calendarContainer, "showTimeInput", e), h.isOpen && le()
                        }
                    })
                }(), h.utils = {
                    getDaysInMonth: function(e, t) {
                        return void 0 === e && (e = h.currentMonth), void 0 === t && (t = h.currentYear), 1 === e && (t % 4 == 0 && t % 100 != 0 || t % 400 == 0) ? 29 : h.l10n.daysInMonth[e]
                    }
                }, h.isMobile || function() {
                    var e = window.document.createDocumentFragment();
                    if (h.calendarContainer = d("div", "flatpickr-calendar"), h.calendarContainer.tabIndex = -1, !h.config.noCalendar) {
                        if (e.appendChild((h.monthNav = d("div", "flatpickr-months"), h.yearElements = [], h.monthElements = [], h.prevMonthNav = d("span", "flatpickr-prev-month"), h.prevMonthNav.innerHTML = h.config.prevArrow, h.nextMonthNav = d("span", "flatpickr-next-month"), h.nextMonthNav.innerHTML = h.config.nextArrow, q(), Object.defineProperty(h, "_hidePrevMonthArrow", {
                                get: function() {
                                    return h.__hidePrevMonthArrow
                                },
                                set: function(e) {
                                    h.__hidePrevMonthArrow !== e && (c(h.prevMonthNav, "flatpickr-disabled", e), h.__hidePrevMonthArrow = e)
                                }
                            }), Object.defineProperty(h, "_hideNextMonthArrow", {
                                get: function() {
                                    return h.__hideNextMonthArrow
                                },
                                set: function(e) {
                                    h.__hideNextMonthArrow !== e && (c(h.nextMonthNav, "flatpickr-disabled", e), h.__hideNextMonthArrow = e)
                                }
                            }), h.currentYearElement = h.yearElements[0], ve(), h.monthNav)), h.innerContainer = d("div", "flatpickr-innerContainer"), h.config.weekNumbers) {
                            var t = function() {
                                    h.calendarContainer.classList.add("hasWeeks");
                                    var e = d("div", "flatpickr-weekwrapper");
                                    e.appendChild(d("span", "flatpickr-weekday", h.l10n.weekAbbreviation));
                                    var t = d("div", "flatpickr-weeks");
                                    return e.appendChild(t), {
                                        weekWrapper: e,
                                        weekNumbers: t
                                    }
                                }(),
                                n = t.weekWrapper,
                                a = t.weekNumbers;
                            h.innerContainer.appendChild(n), h.weekNumbers = a, h.weekWrapper = n
                        }
                        h.rContainer = d("div", "flatpickr-rContainer"), h.rContainer.appendChild($()), h.daysContainer || (h.daysContainer = d("div", "flatpickr-days"), h.daysContainer.tabIndex = -1), J(), h.rContainer.appendChild(h.daysContainer), h.innerContainer.appendChild(h.rContainer), e.appendChild(h.innerContainer)
                    }
                    h.config.enableTime && e.appendChild(function() {
                        h.calendarContainer.classList.add("hasTime"), h.config.noCalendar && h.calendarContainer.classList.add("noCalendar"), h.timeContainer = d("div", "flatpickr-time"), h.timeContainer.tabIndex = -1;
                        var e = d("span", "flatpickr-time-separator", ":"),
                            t = u("flatpickr-hour", {
                                "aria-label": h.l10n.hourAriaLabel
                            });
                        h.hourElement = t.getElementsByTagName("input")[0];
                        var n = u("flatpickr-minute", {
                            "aria-label": h.l10n.minuteAriaLabel
                        });
                        if (h.minuteElement = n.getElementsByTagName("input")[0], h.hourElement.tabIndex = h.minuteElement.tabIndex = -1, h.hourElement.value = i(h.latestSelectedDateObj ? h.latestSelectedDateObj.getHours() : h.config.time_24hr ? h.config.defaultHour : function(e) {
                                switch (e % 24) {
                                    case 0:
                                    case 12:
                                        return 12;
                                    default:
                                        return e % 12
                                }
                            }(h.config.defaultHour)), h.minuteElement.value = i(h.latestSelectedDateObj ? h.latestSelectedDateObj.getMinutes() : h.config.defaultMinute), h.hourElement.setAttribute("step", h.config.hourIncrement.toString()), h.minuteElement.setAttribute("step", h.config.minuteIncrement.toString()), h.hourElement.setAttribute("min", h.config.time_24hr ? "0" : "1"), h.hourElement.setAttribute("max", h.config.time_24hr ? "23" : "12"), h.minuteElement.setAttribute("min", "0"), h.minuteElement.setAttribute("max", "59"), h.timeContainer.appendChild(t), h.timeContainer.appendChild(e), h.timeContainer.appendChild(n), h.config.time_24hr && h.timeContainer.classList.add("time24hr"), h.config.enableSeconds) {
                            h.timeContainer.classList.add("hasSeconds");
                            var a = u("flatpickr-second");
                            h.secondElement = a.getElementsByTagName("input")[0], h.secondElement.value = i(h.latestSelectedDateObj ? h.latestSelectedDateObj.getSeconds() : h.config.defaultSeconds), h.secondElement.setAttribute("step", h.minuteElement.getAttribute("step")), h.secondElement.setAttribute("min", "0"), h.secondElement.setAttribute("max", "59"), h.timeContainer.appendChild(d("span", "flatpickr-time-separator", ":")), h.timeContainer.appendChild(a)
                        }
                        return h.config.time_24hr || (h.amPM = d("span", "flatpickr-am-pm", h.l10n.amPM[o((h.latestSelectedDateObj ? h.hourElement.value : h.config.defaultHour) > 11)]), h.amPM.title = h.l10n.toggleTitle, h.amPM.tabIndex = -1, h.timeContainer.appendChild(h.amPM)), h.timeContainer
                    }()), c(h.calendarContainer, "rangeMode", "range" === h.config.mode), c(h.calendarContainer, "animate", !0 === h.config.animate), c(h.calendarContainer, "multiMonth", h.config.showMonths > 1), h.calendarContainer.appendChild(e);
                    var r = void 0 !== h.config.appendTo && void 0 !== h.config.appendTo.nodeType;
                    if ((h.config.inline || h.config.static) && (h.calendarContainer.classList.add(h.config.inline ? "inline" : "static"), h.config.inline && (!r && h.element.parentNode ? h.element.parentNode.insertBefore(h.calendarContainer, h._input.nextSibling) : void 0 !== h.config.appendTo && h.config.appendTo.appendChild(h.calendarContainer)), h.config.static)) {
                        var l = d("div", "flatpickr-wrapper");
                        h.element.parentNode && h.element.parentNode.insertBefore(l, h.element), l.appendChild(h.element), h.altInput && l.appendChild(h.altInput), l.appendChild(h.calendarContainer)
                    }
                    h.config.static || h.config.inline || (void 0 !== h.config.appendTo ? h.config.appendTo : window.document.body).appendChild(h.calendarContainer)
                }(),
                function() {
                    if (h.config.wrap && ["open", "close", "toggle", "clear"].forEach(function(e) {
                            Array.prototype.forEach.call(h.element.querySelectorAll("[data-" + e + "]"), function(t) {
                                return F(t, "click", h[e])
                            })
                        }), h.isMobile) ! function() {
                        var e = h.config.enableTime ? h.config.noCalendar ? "time" : "datetime-local" : "date";
                        h.mobileInput = d("input", h.input.className + " flatpickr-mobile"), h.mobileInput.step = h.input.getAttribute("step") || "any", h.mobileInput.tabIndex = 1, h.mobileInput.type = e, h.mobileInput.disabled = h.input.disabled, h.mobileInput.required = h.input.required, h.mobileInput.placeholder = h.input.placeholder, h.mobileFormatStr = "datetime-local" === e ? "Y-m-d\\TH:i:S" : "date" === e ? "Y-m-d" : "H:i:S", h.selectedDates.length > 0 && (h.mobileInput.defaultValue = h.mobileInput.value = h.formatDate(h.selectedDates[0], h.mobileFormatStr)), h.config.minDate && (h.mobileInput.min = h.formatDate(h.config.minDate, "Y-m-d")), h.config.maxDate && (h.mobileInput.max = h.formatDate(h.config.maxDate, "Y-m-d")), h.input.type = "hidden", void 0 !== h.altInput && (h.altInput.type = "hidden");
                        try {
                            h.input.parentNode && h.input.parentNode.insertBefore(h.mobileInput, h.input.nextSibling)
                        } catch (e) {}
                        F(h.mobileInput, "change", function(e) {
                            h.setDate(e.target.value, !1, h.mobileFormatStr), ge("onChange"), ge("onClose")
                        })
                    }();
                    else {
                        var e = r(ae, 50);
                        h._debouncedChange = r(Y, M), h.daysContainer && !/iPhone|iPad|iPod/i.test(navigator.userAgent) && F(h.daysContainer, "mouseover", function(e) {
                            "range" === h.config.mode && ne(e.target)
                        }), F(window.document.body, "keydown", te), h.config.inline || h.config.static || F(window, "resize", e), void 0 !== window.ontouchstart ? F(window.document, "touchstart", Z) : F(window.document, "mousedown", N(Z)), F(window.document, "focus", Z, {
                            capture: !0
                        }), !0 === h.config.clickOpens && (F(h._input, "focus", h.open), F(h._input, "mousedown", N(h.open))), void 0 !== h.daysContainer && (F(h.monthNav, "mousedown", N(be)), F(h.monthNav, ["keyup", "increment"], _), F(h.daysContainer, "mousedown", N(se))), void 0 !== h.timeContainer && void 0 !== h.minuteElement && void 0 !== h.hourElement && (F(h.timeContainer, ["increment"], T), F(h.timeContainer, "blur", T, {
                            capture: !0
                        }), F(h.timeContainer, "mousedown", N(P)), F([h.hourElement, h.minuteElement], ["focus", "click"], function(e) {
                            return e.target.select()
                        }), void 0 !== h.secondElement && F(h.secondElement, "focus", function() {
                            return h.secondElement && h.secondElement.select()
                        }), void 0 !== h.amPM && F(h.amPM, "mousedown", N(function(e) {
                            T(e), Y()
                        })))
                    }
                }(), (h.selectedDates.length || h.config.noCalendar) && (h.config.enableTime && I(h.config.noCalendar ? h.latestSelectedDateObj || h.config.minDate : void 0), we(!1)), x(), h.showTimeInput = h.selectedDates.length > 0 || h.config.noCalendar;
            var a = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            !h.isMobile && a && le(), ge("onReady")
        }(), h
    }

    function x(e, t) {
        for (var n = Array.prototype.slice.call(e).filter(function(e) {
                return e instanceof HTMLElement
            }), a = [], i = 0; i < n.length; i++) {
            var o = n[i];
            try {
                if (null !== o.getAttribute("data-fp-omit")) continue;
                void 0 !== o._flatpickr && (o._flatpickr.destroy(), o._flatpickr = void 0), o._flatpickr = y(o, t || {}), a.push(o._flatpickr)
            } catch (e) {
                console.error(e)
            }
        }
        return 1 === a.length ? a[0] : a
    }
    "undefined" != typeof HTMLElement && "undefined" != typeof HTMLCollection && "undefined" != typeof NodeList && (HTMLCollection.prototype.flatpickr = NodeList.prototype.flatpickr = function(e) {
        return x(this, e)
    }, HTMLElement.prototype.flatpickr = function(e) {
        return x([this], e)
    });
    var E = function(e, t) {
        return "string" == typeof e ? x(window.document.querySelectorAll(e), t) : e instanceof Node ? x([e], t) : x(e, t)
    };
    return E.defaultConfig = {}, E.l10ns = {
        en: e({}, a),
        default: e({}, a)
    }, E.localize = function(t) {
        E.l10ns.default = e({}, E.l10ns.default, t)
    }, E.setDefaults = function(t) {
        E.defaultConfig = e({}, E.defaultConfig, t)
    }, E.parseDate = D({}), E.formatDate = v({}), E.compareDates = w, "undefined" != typeof jQuery && void 0 !== jQuery.fn && (jQuery.fn.flatpickr = function(e) {
        return x(this, e)
    }), Date.prototype.fp_incr = function(e) {
        return new Date(this.getFullYear(), this.getMonth(), this.getDate() + ("string" == typeof e ? parseInt(e, 10) : e))
    }, "undefined" != typeof window && (window.flatpickr = E), E
});
window.ISM = window.ISM || {};


/////////////////////////////// Types ///////////////////////////////
ISM.isObject = function(obj) {
    return obj && {}.toString.call(obj) === '[object Object]';
}; //<!>

ISM.isArray = function(array) {
    return array && (Array.isArray && Array.isArray(array)) || {}.toString.call(array) === '[object Array]';
}; //<!>
/////////////////////////////////////////////////////////////////////////////////////////////

// Function return TRUE if page was opened from mobile device, otherwise it return FALSE

ISM.isMobile = function() {
    return /iP(hone|ad|od)|BlackBerry|Palm|Googlebot-Mobile|Mobile|mobile|mobi|Windows Mobile|Safari Mobile|Android|Opera Mini/gi.test(navigator.userAgent.toLowerCase());
}(); //<!>

// Function return 'orientationchange' if page was opened from mobile device, otherwise it return 'resize'
// Example: window[ISM.resizeEvent] = function(){};
// Example: $(window).bind(ISM.resizeEvent, function(){});

ISM.resizeEvent = function() {
    return ISM.isMobile ? 'orientationchange' : 'resize';
}(); //<!>

// Is more convenient way for working with strings that include insertions of variables
// Example: ISM.print_r("first var %0, second - %1", 10, 20)

ISM.print_r = function(str){
    for (var i = 1; i < arguments.length; i++) {
        str = str.replace('%' + (i - 1), arguments[i]);
    }
    return str;
}; // <!>

// Return random value in range between min and max

ISM.random = function(min, max){
    return Math.random() * (max - min) + min;
}; // <!>

// Return random INTEGER value in range between min and max

ISM.randomInt = function(min, max){
    return Math.floor(Math.random() * (max - min)) + min;
}; // <!>

// Return min value in Array
// Example: ISM.minValue([50, 0.25, -100]) -> -100

ISM.minValue = function(arr) {
    var arg = ISM.isArray(arr) ? arr : arguments;
    return Math.min.apply(null, arg);
}; //<!>

// Return max value in Array
// Example: ISM.maxValue([50, 0.25, -100]) -> 50

ISM.maxValue = function(arr) {
    var arg = ISM.isArray(arr) ?  arr : arguments;
    return Math.max.apply(null, arg);
}; //<!>

// You can use that function, for example, on window resize or scroll events fot improving performance
// All arguments after 3rd will be an arguments of called function(arg func);
// Example:
//      var onResize = function(arg1, arg2, arg3){};
//      window.onresize = function(){
//                                  ISM.throttle(onResize, null, 100, arg1, arg2, arg3)
//                             };
// Example:
//      Obj = {};
//      var onResize = function(){ console.log(arguments, '      ', this)};
//      window.onresize = function(){
//                                  ISM.throttle(onResize, Obj, 100, arg1, arg2, arg3)
//                             };
// Result in console: [arg1, arg2, arg3], '      ', Obj

ISM.throttle = function(func, context, time){
    if (func.$__t__$) window.clearTimeout(func.$__t__$);
    var args = ISM.toArray(arguments).splice(3) || [],
        f = func.call(context, args),
        t = time || 100;
    return func.$__t__$ = window.setTimeout('f', t);
}; //<!>

// Just for throwing exceptions. If condition is true, exception will be thrown
// Example:
// var a = "0";
// ISM.assert( typeof a !== "number" , "'a' must be a number");
// -> Will occur exception with message "'a' must be a number"
// var b = 0;
// ISM.assert( typeof b !== "number" , "'a' must be a number");
// -> ISM.assert return true

ISM.assert = function(condition, message){
    if (condition) throw new Error(message);
    return true;
}; //<!>


// Function for preloading of images with possibility to add events.
// handl - is an object of handlers: abort, error, load, beforeLoad
// if the second argument is a function it will be an load function

ISM.loadImage = function(src, handl){
    if(!src) return;
    handl = handl || {};
    if (typeof handl === 'function') handl.load = handl;
    var _img = new Image();
        _img.src = src;
        handl.beforeLoad && handl.beforeLoad(_img);
        if ((_img.readyState && _img.readyState == "complete") || _img.complete === true){
            handl.load && handl.load(_img);
        }else{
            handl.load  && (_img.onload = function() { handl.load(_img)  });
            handl.error && (_img.onerror = function(){ handl.error(_img) });
            handl.abort && (_img.onabort = function(){ handl.abort(_img) });
        }
    return _img;
}; //<!>

///////////////////////////////Array///////////////////////////////

// Convert Object, Arguments, etc. to the new Array. Also, if there are more than 1 argument all arguments of function will be converted ISM.toArray
// Example: ISM.toArray({a:1, b:2, c:3}) -> [1, 2, 3]
// Example: ISM.toArray(1, 2, 3) -> [1, 2, 3]
// Example: ISM.toArray({a:1, b:2, c:3}, 4 , 5) -> [{a:1, b:2, c:3}, 4 , 5]

ISM.toArray = function(arg){
    if (!arg) return;
    if (arguments > 1) arg = arguments;
    var l = arg.length,
        i = 0,
        arr = [];
    if (ISM.isObject(arg)){
        for(i in arg){
            arr.push(arg[i]);
        }
    } else{
        for(i; i < l; i++){
            arr.push(arg[i]);
        }
    }
    return arr;
}; //<!>

// Function for creation 2D Array

ISM.create2DArray = function(rows) {
    var arr = [];
    for (var i = 0; i < rows; arr[i] = [], i++);
    return arr;
}; //<!>

// Check if all positions in Array are equal( including checking of type )
// Example: ISM.equalsValuesInArray([1, 2, 3]) -> false
// Example: ISM.equalsValuesInArray([2, 2, 2]) -> true

ISM.equalsValuesInArray = function(arr) {
    if (!ISM.isArray(arr)) return;
    var i = arr.length;
    while(--i){
        if (arr[i] !== arr[i-1]) return false;
    }
    return true;
}; //<!>

// Random sorting of array

ISM.arrayShuffle = function(o) {
    for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
}; // <!>

// Search in array
// Analog of String.indexOf

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (obj, start) {
        for (var i = (start || 0); i < this.length; i++) {
            if (this[i] == obj) {
                return i;
            }
        }
        return -1;
    }
};

/////////////////////////////////////////////////////////////////////////////////////////////
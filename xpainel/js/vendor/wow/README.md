<link rel="stylesheet" href="<?=HTTP?>/xpainel/js/vendor/wow/css/libs/animate.css">

<script src="<?=HTTP?>/xpainel/js/vendor/wow/dist/wow.js"></script>
<script>
	new WOW().init();
</script>



Class Name			
bounce	flash	pulse	rubberBand
shake	headShake	swing	tada
wobble	jello	bounceIn	bounceInDown
bounceInLeft	bounceInRight	bounceInUp	bounceOut
bounceOutDown	bounceOutLeft	bounceOutRight	bounceOutUp
fadeIn	fadeInDown	fadeInDownBig	fadeInLeft
fadeInLeftBig	fadeInRight	fadeInRightBig	fadeInUp
fadeInUpBig	fadeOut	fadeOutDown	fadeOutDownBig
fadeOutLeft	fadeOutLeftBig	fadeOutRight	fadeOutRightBig
fadeOutUp	fadeOutUpBig	flipInX	flipInY
flipOutX	flipOutY	lightSpeedIn	lightSpeedOut
rotateIn	rotateInDownLeft	rotateInDownRight	rotateInUpLeft
rotateInUpRight	rotateOut	rotateOutDownLeft	rotateOutDownRight
rotateOutUpLeft	rotateOutUpRight	hinge	jackInTheBox
rollIn	rollOut	zoomIn	zoomInDown
zoomInLeft	zoomInRight	zoomInUp	zoomOut
zoomOutDown	zoomOutLeft	zoomOutRight	zoomOutUp
slideInDown	slideInLeft	slideInRight	slideInUp
slideOutDown	slideOutLeft	slideOutRight	slideOutUp
heartBeat			
Full example:

<h1 class="animated infinite bounce delay-2s">Example</h1>
Check out all the animations here!

It's possible to change the duration of your animations, add a delay or change the number of times that it plays:

.yourElement {
  animation-duration: 3s;
  animation-delay: 2s;
  animation-iteration-count: infinite;
}
Usage with jQuery
You can do a whole bunch of other stuff with animate.css when you combine it with jQuery. A simple example:

$('#yourElement').addClass('animated bounceOutLeft');
You can also detect when an animation ends:

// See https://github.com/daneden/animate.css/issues/644
var animationEnd = (function(el) {
  var animations = {
    animation: 'animationend',
    OAnimation: 'oAnimationEnd',
    MozAnimation: 'mozAnimationEnd',
    WebkitAnimation: 'webkitAnimationEnd',
  };

  for (var t in animations) {
    if (el.style[t] !== undefined) {
      return animations[t];
    }
  }
})(document.createElement('div'));

$('#yourElement').one(animationEnd, doSomething);
View a video tutorial on how to use Animate.css with jQuery here.

Note: jQuery.one() is used when you want to execute the event handler at most once. More information here.

It's possible to extend jQuery and add a function that does it all for you:

$.fn.extend({
  animateCss: function(animationName, callback) {
    var animationEnd = (function(el) {
      var animations = {
        animation: 'animationend',
        OAnimation: 'oAnimationEnd',
        MozAnimation: 'mozAnimationEnd',
        WebkitAnimation: 'webkitAnimationEnd',
      };

      for (var t in animations) {
        if (el.style[t] !== undefined) {
          return animations[t];
        }
      }
    })(document.createElement('div'));

    this.addClass('animated ' + animationName).one(animationEnd, function() {
      $(this).removeClass('animated ' + animationName);

      if (typeof callback === 'function') callback();
    });

    return this;
  },
});
And use it like this:

$('#yourElement').animateCss('bounce');
or;
$('#yourElement').animateCss('bounce', function() {
  // Do something after animation
});
Setting Delay and Speed
Delay Class
It's possible to add delays directly on the element's class attribute, just like this:

<div class="animated bounce delay-2s">Example</div>
Class Name	Delay Time
delay-2s	2s
delay-3s	3s
delay-4s	4s
delay-5s	5s
Note: The default delays are from 1 second to 5 seconds only. If you need custom delays, add it directly to your own CSS code.

Slow, Slower, Fast, and Faster Class
It's possible to control the speed of the animation by adding these classes, as a sample below:

<div class="animated bounce faster">Example</div>
Class Name	Speed Time
slow	2s
slower	3s
fast	800ms
faster	500ms
Note: The animated class has a default speed of 1s. If you need custom duration, add it directly to your own CSS code.

Custom Builds
Animate.css is powered by gulp.js, which means you can create custom builds pretty easily. First of all, you’ll need Gulp and all other dependencies:

$ cd path/to/animate.css/
$ sudo npm install
Next, run gulp to compile your custom builds. For example, if you want only some of the “attention seekers”, simply edit the animate-config.json file to select only the animations you want to use.

"attention_seekers": {
  "bounce": true,
  "flash": false,
  "pulse": false,
  "shake": true,
  "headShake": true,
  "swing": true,
  "tada": true,
  "wobble": true,
  "jello":true
}



# WOW.js [![Build Status](https://secure.travis-ci.org/graingert/WOW.svg?branch=master)](http://travis-ci.org/graingert/WOW)

Temporary deprecation:
======================

wow.js is temporarily deprecated in favour of AOS (Animate on Scroll). Feel free to ignore the warning if you can't use AOS.

Plans for 3.0 include:

* Breaking out the shims into an optional module
* Using the AOS approach for most functionality
* Remain completely backwards compatible and a drop-in replacement for GPL wowjs, but issue warning on durations of higher granularity than 50ms
or longer than 3s
* Detect Firefox for Android as mobile.


Reveal CSS animation as you scroll down a page.
By default, you can use it to trigger [animate.css](https://github.com/daneden/animate.css) animations.
But you can easily change the settings to your favorite animation library.

Advantages:
- 100% MIT Licensed, not GPL keep your code yours.
- Naturally Caffeine free
- Smaller than other JavaScript parallax plugins, like Scrollorama (they do fantastic things, but can be too heavy for simple needs)
- Super simple to install, and works with animate.css, so if you already use it, that will be very fast to setup
- Fast execution and lightweight code: the browser will like it ;-)
- You can change the settings - [see below](#advanced-usage)

### [LIVE DEMO ➫](https://graingert.co.uk/WOW/)

## Live examples
- [MaterialUp](http://www.materialup.com)
- [Fliplingo](https://www.fliplingo.com)
- [Streamline Icons](http://www.streamlineicons.com)
- [Microsoft Stories](http://www.microsoft.com/en-us/news/stories/garage/)


## Documentation

It just take seconds to install and use WOW.js!
[Read the documentation ➫](https://graingert.co.uk/WOW/docs.html)

### Dependencies
- [animate.css](https://github.com/daneden/animate.css)

### Installation

- Bower

```bash
   bower install wow-mit
```

- NPM

```bash
   npm install wow.js
```

### Basic usage

- HTML

```html
  <section class="wow slideInLeft"></section>
  <section class="wow slideInRight"></section>
```

- JavaScript

```javascript
new WOW().init();
```

### Advanced usage

- HTML

```html
  <section class="wow slideInLeft" data-wow-duration="2s" data-wow-delay="5s"></section>
  <section class="wow slideInRight" data-wow-offset="10"  data-wow-iteration="10"></section>
```

- JavaScript

```javascript
var wow = new WOW(
  {
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       true,       // trigger animations on mobile devices (default is true)
    live:         true,       // act on asynchronously loaded content (default is true)
    callback:     function(box) {
      // the callback is fired every time an animation is started
      // the argument that is passed in is the DOM node being animated
    },
    scrollContainer: null,    // optional scroll container selector, otherwise use window,
    resetAnimation: true,     // reset animation on end (default is true)
  }
);
wow.init();
```

### Asynchronous content support

In IE 10+, Chrome 18+ and Firefox 14+, animations will be automatically
triggered for any DOM nodes you add after calling `wow.init()`. If you do not
like that, you can disable this by setting `live` to `false`.

If you want to support older browsers (e.g. IE9+), as a fallback, you can call
the `wow.sync()` method after you have added new DOM elements to animate (but
`live` should still be set to `true`). Calling `wow.sync()` has no side
effects.


## Contribute

The library is transpiled using Babel, please update `wow.js` file.

We use grunt to compile and minify the library:

Install needed libraries

```
npm install
```

Get the compilation running in the background

```
grunt watch
```

Enjoy!

## Bug tracker

If you find a bug, please report it [here on Github](https://github.com/graingert/WOW/issues)!

## Developer

Originally Developed by Matthieu Aussaguel, [mynameismatthieu.com](http://mynameismatthieu.com)
Forked to remain under the MIT license by Thomas Grainger, https://graingert.co.uk

+ [Github Profile](//github.com/graingert)

## Contributors

Thanks to everyone who has contributed to the project so far:

- Attila Oláh - [@attilaolah](//twitter.com/attilaolah) - [Github Profile](//github.com/attilaolah)
- [and many others](//github.com/graingert/WOW/graphs/contributors)

Initiated and designed by [Vincent Le Moign](//www.webalys.com/), [@webalys](//twitter.com/webalys)

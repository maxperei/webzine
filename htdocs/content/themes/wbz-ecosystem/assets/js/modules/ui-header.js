const UIHeader = (function() {
    let _el;
    let _stickyClass = 'header--sticky';
    // Soit l'élément du DOM auquel est ajouté la _stickyClass
    // 'self' (sera évalué à _el) ou un élément HTML (document.body par ex.)
    let _stickyClassTarget = 'self';

    let _sticky = false;
    let _fixed = false;

    // Soit la hauteur du header normal
    let _threshold = 56;

    // Soit la hauteur du header compact
    let _amplitude = 28;

    let _windowScroll = 0;
    let _lastScroll = 0;
    let _lockOnScroll = 0;
    let _direction = 1;
    let _speed = 0;
    let _delta = 0;

    function _cacheWindowScroll (ev) {
        _windowScroll = window.scrollY || window.pageYoffset || document.documentElement.scrollTop || document.body.scrollTop;
        _doYourThing();
    }

    function _doYourThing () {
        _speed = Math.abs(_windowScroll - _lastScroll);

        if(_windowScroll > _threshold && !_sticky) {
            // console.log('Going from static to sticky');
            _sticky = true;
            _stickyClassTarget.classList.add(_stickyClass);
        }
        else if(_windowScroll <= 0 && _sticky) {
            // console.log('Going from sticky to static');
            _reset();
        }

        if(_windowScroll > _lastScroll) {
            // scrolling down
            if(_direction > 0) {
                if(_sticky) {
                    _delta = Math.abs(_windowScroll - _lockOnScroll);

                    if(!_fixed && _delta >= _amplitude) {
                        // console.log('Switching position type');
                        _fixed = true;
                        _el.style.position = '';
                        _el.style.top = 0;
                    }
                }
            }
            else {
                // Direction changed
                // console.log('Direction changed. Now scrolling down.');
                _direction = 1;
                _lockOnScroll = _windowScroll;

                if(!_fixed) {
                    _lockOnScroll += (_amplitude - _delta);
                }
                else if(_sticky && _fixed) {
                    _fixed = false;
                    _el.style.top = _windowScroll + 'px';
                    _el.style.position = 'absolute';
                }
            }
        }
        else if(_windowScroll < _lastScroll) {
            // scrolling up
            if(_direction < 0) {
                if(_sticky) {
                    _delta = Math.abs(_windowScroll - _lockOnScroll);

                    if(!_fixed && _delta >= _amplitude) {
                        // console.log('Switching position type');
                        _fixed = true;
                        _el.style.top = 0;
                        _el.style.position = 'fixed';
                    }
                }
            }
            else {
                // Direction changed
                // console.log('Direction changed. Now scrolling up.');
                _direction = -1;
                _lockOnScroll = _windowScroll;

                if(!_fixed) {
                    _lockOnScroll += (_amplitude - _delta);
                }
                else if(_sticky && _fixed) {
                    _fixed = false;
                    _el.style.top = (_windowScroll - _amplitude) + 'px';
                    _el.style.position = 'absolute';
                }
            }
        }

        _lastScroll = _windowScroll;
    }

    function _reset () {
        _el.style.position = '';
        _el.style.top = '';
        _stickyClassTarget.classList.remove(_stickyClass);
        _sticky = false;
        _fixed = false;
        _windowScroll = 0;
        _lastScroll = 0;
        _lockOnScroll = 0;
        _direction = 1;
    }

    function init (el) {
        if(!_el) {
            _el = el;

            if(_stickyClassTarget == 'self') {
                _stickyClassTarget = _el;
            }

            _cacheWindowScroll();
            _lastScroll = _windowScroll;
            _doYourThing();
            window.addEventListener('scroll', _cacheWindowScroll);
        }
    }

    function tearDown () {
        if(_el) {
            window.removeEventListener('scroll', _cacheWindowScroll);
            _reset();
            _el = null;
        }
    }

    return {
        init: init,
        tearDown: tearDown
    };
})();

export { UIHeader };
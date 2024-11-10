var calc = (function () {
    var $btn    = document.querySelectorAll('#cal'),
        $input  = document.querySelector('#inputcal');

    var _reset = function () {
        $input.value = 0;
    };

    var _isReset = function(val){
        return !!~val.indexOf('C');
    };

    var _isNumber = function(val){
        return !isNaN(Number(val));
    };

    var _isDot = function(val){
        return !!~val.indexOf('.');
    };

    var _isOperator = function(val){
        var _operators = ['+', '-', 'x', '÷'];
        return !!~_operators.join('').indexOf(val);
    };

    var _isEqually = function(val){
        return !!~val.indexOf('=');
    };

    var _getLastSymbol = function(){
        return $input.value[$input.value.length - 1];
    };

    var _replacesOperator = function(val){
        return val.replace(/x/g, '*').replace(/÷/g, '/');
    };

    var _isRemove = function(val){
        return !!~'←'.indexOf(val);
    };

    var _removeSymbol = function(){
        if($input.value && $input.value !== '0'){
            $input.value = $input.value.substr(0, $input.value.length - 1);
        }
        !$input.value.length ? _reset() : null;
    };

    var _draw = function (val) {
        if(+$input.value === 0 && !_isOperator(val) && !_isDot(val) && !_isDot(_getLastSymbol())){
            $input.value = val;
        }else if(+$input.value === 0 && _isDot(val)){
            $input.value = $input.value + val;
        }else if(_isDot(_getLastSymbol())){
            $input.value = $input.value + val;
        }else if($input.value && +$input.value !== 0){
            if(_isNumber(val)){
                $input.value = $input.value + val;
            }else if( !_isOperator(_getLastSymbol()) && _isOperator(val)){
                $input.value = $input.value + val;
            }else if(_isOperator(_getLastSymbol()) && _isOperator(val)){
                $input.value = $input.value.replace(_getLastSymbol(), val);
            }else if(!_isDot(_getLastSymbol()) ||  !_isOperator(_getLastSymbol()) && _isDot(val)){
                $input.value = $input.value + val;
            }
        }
    };

    var _getEquall = function(){
        $input.value = eval(_replacesOperator($input.value));
    };

    var _addEvent = function () {
        $btn.forEach(function (el) {
            el.addEventListener('click',function(e){
                e.preventDefault();
                var value = this.textContent;
                if(_isEqually(value)){
                    _getEquall();
                }else if(_isReset(value)){
                    _reset();
                }else if(_isRemove(value)){
                    _removeSymbol();
                }else {
                    _draw(value);
                }
            }, false);
        })
    };

    var init = function () {
        _addEvent();
    };

    return {
        init: init
    }

}());

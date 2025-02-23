var defaultPasswordMinLength = 6;
var defaultPasswordMaxLength = 16;
function password_generator( number, specialChars, len ) {
    var length = (len)?(len):(defaultPasswordMinLength);
    var string = "abcdefghijklmnopqrstuvwxyz"; //to upper 
    var numeric = (number) ? '0123456789' : '';
    var punctuation = (specialChars) ? '!@#$%^&*()_+~`|}{[]\:;?><,./-=' : '';
    var password = "";
    var character = "";
    var crunch = true;
    while( password.length<length ) {
        entity1 = Math.ceil(string.length * Math.random()*Math.random());
        if(number){
        entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
        }else{
            entity2 = "";
        }
        if(specialChars){
        entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
        }else{
            entity3 = "";
        }
        hold = string.charAt( entity1 );
        hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
        character += hold;
        if(number){
        character += numeric.charAt( entity2 );
        }
        if(specialChars){
        character += punctuation.charAt( entity3 );
        }
        password = character;
    }
    password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
    return password.substr(0,len);
}

function generatePassword(pwInput, useNumberInput, useSpecialCharsInput, passwordLength, newPwInput, newPwInput2){
    var number = true;
    var specialChars = false;
    var length = $(passwordLength).val();
    if(length < defaultPasswordMinLength){
        length = defaultPasswordMinLength;
    }
    if(length > defaultPasswordMaxLength){
        length = defaultPasswordMaxLength
    }

    var number = $(useNumberInput).is(":checked") ? true : false;
    var specialChars = $(useSpecialCharsInput).is(":checked") ? true : false;

    var pw = toUpperCase(password_generator(number, specialChars, length));
    $(pwInput).val(pw);

    clearInputValue(newPwInput);
    clearInputValue(newPwInput2);
}

function usePassword(pwInput, newPwInput, newPwInput2){
    var pw = $(pwInput).val();
    if(pw.length > 0){
        $(newPwInput).val(pw);
        $(newPwInput).removeClass("is-invalid");
        if(newPwInput2){
            $(newPwInput2).val(pw);
            $(newPwInput2).removeClass("is-invalid");
        }
    }
}

function clearPassword(pwInput, newPwInput, newPwInput2){
    $(pwInput).val('');
    $(newPwInput).val('');
    $(newPwInput2).val('');
}

function toUpperCase(str) {
    return str.split('').map((v, i) => i % 2 == 0 ? v.toLowerCase() : v.toUpperCase()).join('');
}

function clearInputValue(input){
    $(input).val('');
}
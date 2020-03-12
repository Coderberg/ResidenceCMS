$(document).ready(function () {

    'use strict';

    function transliterate(str) {

        let transliterated = [];
        let ru = {
            'а': 'a', 'б': 'b', 'в': 'v',
            'г': 'g', 'д': 'd', 'е': 'e',
            'ё': 'e', 'ж': 'zh', 'з': 'z',
            'и': 'i', 'й': 'y', 'к': 'k',
            'л': 'l', 'м': 'm', 'н': 'n',
            'о': 'o', 'п': 'p', 'р': 'r',
            'с': 's', 'т': 't', 'у': 'u',
            'ф': 'f', 'х': 'h', 'ц': 'ts',
            'ч': 'ch', 'ш': 'sh', 'щ': 'shch',
            'ы': 'y', 'э': 'e', 'ю': 'iu',
            'я': 'ya', 'ь': '', 'ъ': ''
        };

        for (let i = 0; i < str.length; ++i) {
            transliterated.push(
                ru[str[i]]
                || ru[str[i].toLowerCase()] === undefined && str[i]
                || ru[str[i].toLowerCase()].replace(/^(.)/, function (match) {
                    return match.toUpperCase();
                })
            );
        }

        return transliterated.join('');
    }

    function slugify(str) {

        return str
            .replace(/^\s+|\s+$/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9 -]/g, '-')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-/, '')
            .replace(/-$/, '');
    }

    $("#name input").keyup(function () {

        let name = transliterate($("#name input").val());

        $("#slug input").val(slugify(name));
    });

});

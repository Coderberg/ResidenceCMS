function slugify() {
    var n = "-", t = $("#name input").val().toLowerCase(), r = {
        "а": "a",
        "б": "b",
        "в": "v",
        "г": "g",
        "д": "d",
        "е": "e",
        "ё": "e",
        "ж": "zh",
        "з": "z",
        "и": "i",
        "й": "j",
        "к": "k",
        "л": "l",
        "м": "m",
        "н": "n",
        "о": "o",
        "п": "p",
        "р": "r",
        "с": "s",
        "т": "t",
        "у": "u",
        "ф": "f",
        "х": "h",
        "ц": "c",
        "ч": "ch",
        "ш": "sh",
        "щ": "sh",
        "ъ": n,
        "ы": "y",
        "ь": n,
        "э": "e",
        "ю": "yu",
        "я": "ya",
        " ": n,
        _: n,
        "`": n,
        "~": n,
        "!": n,
        "@": n,
        "#": n,
        $: n,
        "%": n,
        "^": n,
        "&": n,
        "*": n,
        "(": n,
        ")": n,
        "-": n,
        "=": n,
        "+": n,
        "[": n,
        "]": n,
        "\\": n,
        "|": n,
        "/": n,
        ".": n,
        ",": n,
        "{": n,
        "}": n,
        "'": n,
        '"': n,
        ";": n,
        ":": n,
        "?": n,
        "<": n,
        ">": n,
        "№": n
    }, e = "", u = "";
    for (i = 0; i < t.length; i++) null != r[t[i]] ? u == r[t[i]] && u == n || (e += r[t[i]], u = r[t[i]]) : (e += t[i], u = t[i]);
    e = TrimStr(e), $("#slug input").val(e)
}

function TrimStr(i) {
    return (i = i.replace(/^-/, "")).replace(/-$/, "")
}

$(function () {
    $("#name input").keyup(function () {
        return slugify(), !1
    })
});

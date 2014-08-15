/**
 * A função recebe dados no formato descrito e executa a chamada do ajax via post
 * var dados = {
 *              objeto: 'NomeDoObjeto', 
 *              method: 'metodoDoObjeto', 
 *              param1: value, 
 *              param2: value, 
 *              param3: value,
 *              ... 
 *          };
 * 
 * @param {array} dados Dados da aplicacao
 * @return {array | boollean} result
 */
function AjaxPost(dados) {

    var result = false;

    $.ajax({
        type: "POST",
        url: "index.php?c=Ajax&a=ajaxPost",
        async: false,
        data: {
            dados: dados
        },
        dataType: "json",
        success: function(retorno) {
            result = retorno;
        }
    });

    return result;

}

/**
 * A função recebe dados no formato descrito e executa a chamada do ajax via post
 * var dados = {
 *              session: 'NomeDaSession', 
 *              value: 'valorDaSession'
 *          };
 * 
 * @param {array} dados Dados da aplicacao
 * @return {array | boollean} result
 */
function AjaxSession(dados) {

    var result = false;

    $.ajax({
        type: "POST",
        url: "index.php?c=Ajax&a=ajaxSession",
        async: false,
        data: {
            dados: dados
        },
        dataType: "json",
        success: function(retorno) {
            result = retorno;
        }
    });

    return result;

}

/*
 * A função instancia o retorno da consulta no selector instanciado
 * 
 * Nome do selector que receberar o resultado da function
 * @param {text} selector
 * Nome do Objeto a qual a função vai pesquisar
 * @param {text} objeto
 * Nome do method que será instanciado no objeto
 * @param {text} method
 * Dados restantes para completar a url de consulta
 * @param {text} dados
 * @returns {void}
 */
function mountDataTable(selector, objeto, method, dados) {

    if (typeof dados === 'undefined') {
        dados = '';
    } else {
        dados = '&' + dados;
    }

    $(selector).dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "destroy": true,
        "sAjaxSource": "index.php?c=Ajax&a=ajaxGet&objeto=" + objeto + "&method=" + method + dados,
        "language": {
            "sSearch": "Filtrar",
            "info": "Pagina _PAGE_ de _PAGES_ de _MAX_ resultado(s)",
            "infoEmpty": "Pagina 0 a 0 de 0 resultado(s)",
            "infoFiltered": "",
            "sZeroRecords": "Nenhum resultado que corresponde ao criterio foi encontrado",
            "processing": "Processando",
            "emptyTable": "Não existe dados associados",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Próximo",
                "sLast": "Último"
            }
        }
    });

}

/* Retorna a url da pagina atual */
function getUrl() {
    var url = window.location;
    return url.toString();
}

/* Limpar url deixando só os paravemtros */
function getArrayUrlParams() {
    var url = getUrl();
    var dividirUrl = url.split("?");
    dividirUrl.shift();
    var arrayParams = dividirUrl[0].split("/");
    arrayParams.shift();
    arrayParams.shift();

    var iUltimorrayParams = arrayParams.length - 1;

    if (arrayParams[iUltimorrayParams] === "") {
        arrayParams.pop();
    }

    var params = new Array();

    while (arrayParams.length > 1) {
        params[arrayParams[0]] = arrayParams[1];
        arrayParams.shift();
        arrayParams.shift();
    }
    
    return params;
}

function getUrlParam(param) {
    var params = getArrayUrlParams();
    return params[param];
}

/* Monta a lista de option de acordo ao json passado (Modelo para o plugin)*/
function ListSelect2Option(selection, fromObject, AjaxParams) {
    if (selection !== '') {

        var ObjectJson = AjaxPost(AjaxParams);
        if (ObjectJson) {
            var options = '<option value="">' + fromObject['msgSuccess'] + '</option>';
            var tDados = ObjectJson.length;
            for (var j = 0; j < tDados; j++) {
                options += '<option value="' + ObjectJson[j].value + '">' + ObjectJson[j].nome + '</option>';
            }
            fromObject['object'].html('<span class="select2-chosen">' + fromObject['msgSuccess'] + '</span><abbr class="select2-search-choice-close"></abbr>');
            $(fromObject['id']).html(options).show();
        } else {
            fromObject['object'].html('<span class="select2-chosen">' + fromObject['msgError'] + '</span><abbr class="select2-search-choice-close"></abbr>');
            $(fromObject['id']).html('<option value="" >' + fromObject['msgError'] + '</option>');
        }

    } else {
        fromObject['object'].html('<span class="select2-chosen">' + fromObject['msgEmpty'] + '</span><abbr class="select2-search-choice-close"></abbr>');
        $(fromObject['id']).html('<option value="" >' + fromObject['msgEmpty'] + '</option>');
    }
}

function Maiusculo(campo)
{
    $(campo).keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
}

function Minusculo(campo)
{
    $(campo).keyup(function() {
        $(this).val($(this).val().toLowerCase());
    });
}

function replaceAll(str, de, para) {
    var pos = str.indexOf(de);
    while (pos > -1) {
        str = str.replace(de, para);
        pos = str.indexOf(de);
    }
    return (str);
}

function formatMonetario(valor) {

    valor = replaceAll(valor, 'R$ ', '');
    valor = replaceAll(valor, '.', '');
    valor = replaceAll(valor, ',', '.');
    valor = parseFloat(valor);

    if (valor > 0) {
        return valor.toFixed(2);
    } else {
        return '0.00';
    }

}

function apenasNumeros(campo)
{
    $(campo).keyup(function() {
        // EXPRESSAO REGULAR PARA ACEITAR APENAS NUMEROS INTEIROS
        var reDigits = /^\d+$/;
        var valor = $(this).val();
        var numeric = reDigits.test(valor);

        if (!numeric) {
            var tValor = valor.length;
            valor = valor.substring(0, tValor - 1);
            $(this).val(valor);
        }
    });
}


function float2moeda(num) {

    x = 0;

    if (num < 0) {
        num = Math.abs(num);
        x = 1;
    }
    if (isNaN(num))
        num = "0";

    cents = Math.floor((num * 100 + 0.5) % 100);

    num = Math.floor((num * 100 + 0.5) / 100).toString();

    if (cents < 10)
        cents = "0" + cents;

    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));

    ret = num + ',' + cents;

    if (x == 1)
        ret = ' - ' + ret;

    return ret;

}

/* Habilitar um button */
function buttonEnabled(selector) {
    $(selector).removeAttr("class");
    $(selector).attr("class", "large primary");
    $(selector).removeAttr("disabled");
}

/* Desabilitar um button */
function buttonDisabled(selector) {
    $(selector).removeAttr("class");
    $(selector).attr("class", "large");
    $(selector).attr("disabled", "disabled");
}

/* Habilitar um item */
function enabled(selector) {
    $(selector).removeAttr("disabled");
}

/* Desabilitar um item */
function disabled(selector) {
    $(selector).attr("disabled", "disabled");
}

/**
 * Criar uma tela modal com o conteudo de uma pagina<br>
 *  passada pelo atributo url 
 * @param {text} title
 * @param {text} icon
 * @param {text} url
 * @returns {void}
 */
function dialogFlatIframe(title, icon, url) {
    $.Dialog({
        overlay: true,
        shadow: true,
        flat: true,
        icon: icon,
        title: title,
        content: '',
        onShow: function(_dialog) {
            var html = [
                '<iframe width="768" height="480" src="' + url + '" frameborder="0" allowfullscreen></iframe>'
            ].join("");

            $.Dialog.content(html);
        }
    });
}

function closeDialogFlatIframe() {
    $(".btn-close").click();
}

/* Não permite duplo clique no submit */
jQuery.fn.preventDoubleSubmit = function() {
    jQuery(this).submit(function() {
        if (this.beenSubmitted)
            return false;
        else
            this.beenSubmitted = true;
    });
};

function reloadUrl() {
    document.location.reload(true);
}
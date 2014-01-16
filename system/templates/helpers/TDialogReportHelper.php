<?php

/**
 * @package HELPERS 
 */
class TDialogReportHelper {

    public static function showButtonDialogReport($acao, array $type = null) {

        $buttonDialog = TDialogReportHelper::gerarJsReport($acao, $type);
        $buttonDialog .= TDialogReportHelper::criarButton();

        return $buttonDialog;
    }

    public static function criarButton($value = 'Gerar Relatório') {

        $button = '<button id = "dialogReport" class = "image-button fg-color-white" style = "float: right;margin: 0px; background:#008287">';
        $button .= $value;
        $button .= '<i class = "icon-clipboard-2 bg-color-darken"></i>';
        $button .= '</button>';

        return $button;
    }

    public static function gerarJsReport($acao, array $type = null) {
        if ($type === null) {
            $type[] = 'html';
        } else {
            array_unshift($type, 'html');
        }

        $iconExt = array(
            'html' => array(
                'icon' => 'icon-libreoffice',
                'label' => 'Visualizar'
            ),
            'pdf' => array(
                'icon' => 'icon-file-pdf',
                'label' => 'PDF'
            ),
            'xls' => array(
                'icon' => 'icon-file-excel',
                'label' => 'Excel'
            )
        );

        $javaScript = "<script>";

        foreach ($type as $ext) {
            $javaScript .= '$(document).on("click", "button#' . $ext . '", function() {';

            $javaScript .= 'var theForm = document.forms["form_filtro"];';
            $javaScript .= 'var input = document.createElement("input");';
            $javaScript .= 'input.type = "hidden";';
            $javaScript .= 'input.name = "type";';
            $javaScript .= 'input.value = "' . $ext . '";';
            $javaScript .= 'theForm.appendChild(input);';

            $javaScript .= '$.ajax({';

            $javaScript .= 'url: "index.php?Relatorio/ajaxSession",';
            $javaScript .= 'type: "post",';
            $javaScript .= 'dataType: "json",';
            $javaScript .= 'data: $("#form_filtro").serialize(),';

            $javaScript .= 'success: function(data) {';
            $javaScript .= '$("input[name=type]").remove();';
            $javaScript .= 'if (data === true) {';
            $javaScript .= 'window.open("index.php?' . CurrentSystemHelper::getCurrentController() . '/' . $acao . '", "_blank");';
            $javaScript .= '}';
            $javaScript .= '}';

            $javaScript .= '});';

            $javaScript .= '});';
        }


        /**
         * Criar Dialog com o ToolBar
         */
        $javaScript .= "$(document).ready(function() {";

        $javaScript .= "$('#dialogReport').click(function(e) {";

        $javaScript .= 'var html = "";';
        $javaScript .= 'html += "<div style=\'text-align: center;\'>";';

            foreach ($type as $ext) {

                $javaScript .= "html += \"<button class='shortcut' id='{$ext}'>";

                $javaScript .= "<span class='icon'>";
                $javaScript .= "<i class='{$iconExt[$ext]['icon']}'></i>";
                $javaScript .= "</span>";

                $javaScript .= "<span class='label'>{$iconExt[$ext]['label']}</span>";

                $javaScript .= "</button>\";";
            }
        
        $javaScript .= 'html += "</div>";';

        $javaScript .= "$.Dialog({";

        $javaScript .= "'title': 'Saída de Relatório',";
        $javaScript .= "'content': html,";

        $javaScript .= "'buttons': {";
        $javaScript .= "'Cancelar': {";
        $javaScript .= "'action': function() {}";
        $javaScript .="}";
        $javaScript .= "}";

        $javaScript .= "});";

        $javaScript .= "});";
        $javaScript .= "});";

        $javaScript .= "</script>";


        return $javaScript;
    }

}

?>

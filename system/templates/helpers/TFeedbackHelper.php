<?php

/**
 * Class responsavel pelos feedback predefinidos no sistema
 * @author igorsantos
 */
class TFeedbackHelper {

    public static function isFeedback() {
        if (isset($_SESSION['feedback'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna o feedback
     * <ul>
     *  <li>creatFeedback(0); -> Ocorreu um problema ao tentar salvar as informações.</li>
     *  <li>creatFeedback(1); -> Dados salvo na base de dados.</li>
     *  <li>creatFeedback(2); -> Email enviado com dados para recuperação de senha.</li>
     *  <li>creatFeedback(3); -> Solicitação de alteração de senha concluida.</li>
     * </ul>
     */
    public static function creatFeedback($value) {
        $_SESSION['feedback'] = $value;
    }

    public static function creatFeedbackOK($mensagem) {
        $_SESSION['feedback']['ok'] = $mensagem;
    }

    public static function creatFeedbackError($mensagem) {
        $_SESSION['feedback']['error'] = $mensagem;
    }

    public static function creatFeedbackWarning($mensagem) {
        $_SESSION['feedback']['warning'] = $mensagem;
    }

    public static function deleteFeedback() {
        unset($_SESSION['feedback']);
    }

    public static function getFeedback() {
        return $_SESSION['feedback'];
    }

    public static function mensagemOK($mensagem) {

        $msg = '';
        $msg .= '<div id="tfeedback" class="alert alert-success">';
        $msg .= '<button type="button" class="close" data-dismiss="alert">×</button>';
        $msg .= '<strong>Sucesso!!! </strong>' . $mensagem ;
        $msg .='</div>';

        return $msg;
    }

    public static function mensagemError($mensagem) {

        $msg = '';
        $msg .= '<div id="tfeedback" class="alert alert-error">';
        $msg .= '<button type="button" class="close" data-dismiss="alert">×</button>';
        $msg .= '<strong>Erro!!! </strong>' . $mensagem ;
        $msg .='</div>';

        return $msg;
    }

    public static function mensagemWarning($mensagem) {

        $msg = '';
        $msg .= '<div id="tfeedback" class="alert alert-block">';
        $msg .= '<button type="button" class="close" data-dismiss="alert">×</button>';
        $msg .= '<strong>Advertência!!! </strong>' . $mensagem ;
        $msg .='</div>';

        return $msg;
    }

    /**
     * Retorna o feedback
     * 0 - Ocorreu um problema ao tentar salvar as informações. <br>
     * 1 - Dados salvo na base de dados. <br>
     * 2 - Email enviado com dados para recuperação de senha. <br>
     * 3 - Solicitação de alteração de senha concluida. <br>
     * @return feedback
     */
    public static function displayFeedback() {

        $feedBack = '';
        if (TFeedbackHelper::isFeedback()) {
            switch (TFeedbackHelper::getFeedback()) {

                # Problema ao salvar informações
                case 0:
                    $feedBack = TFeedbackHelper::mensagemError('Ocorreu um problema ao tentar salvar as informações.');
                    break;

                # Envio de email
                case 1:
                    $feedBack = TFeedbackHelper::mensagemOK('Dados salvos!');
                    break;

                # Envio de email
                case 2:
                    $feedBack = TFeedbackHelper::mensagemOK('Email enviado com dados para recuperação de senha.');
                    break;

                # Solicitação de alteração de senha
                case 3:
                    $feedBack = TFeedbackHelper::mensagemOK('Solicitação de alteração de senha concluida.');
                    break;

                default:
                    if (isset($_SESSION['feedback']['ok'])) {
                        $feedBack = TFeedbackHelper::mensagemOK($_SESSION['feedback']['ok']);
                    } else if (isset($_SESSION['feedback']['error'])) {
                        $feedBack = TFeedbackHelper::mensagemError($_SESSION['feedback']['error']);
                    } else if (isset($_SESSION['feedback']['warning'])) {
                        $feedBack = TFeedbackHelper::mensagemWarning($_SESSION['feedback']['warning']);
                    }
                    break;
            }

            TFeedbackHelper::deleteFeedBack();
        }

        return $feedBack;
    }

}

?>

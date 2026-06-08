<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario"])) {
    header("Location: /Git/Xhoppi/view/login.php");
    exit;
}

function nivelUsuarioLogado()
{
    return isset($_SESSION["usuario"]["nivel_acesso"]) ? normalizarNivelAcesso($_SESSION["usuario"]["nivel_acesso"]) : "";
}

function usuarioTemNivel($niveisPermitidos)
{
    if (!is_array($niveisPermitidos)) {
        $niveisPermitidos = array($niveisPermitidos);
    }

    $niveisPermitidos = array_map("normalizarNivelAcesso", $niveisPermitidos);

    return in_array(nivelUsuarioLogado(), $niveisPermitidos);
}

function exigirNivel($niveisPermitidos)
{
    if (!usuarioTemNivel($niveisPermitidos)) {
        header("Location: /Git/Xhoppi/index.php?acesso=negado");
        exit;
    }
}

function normalizarNivelAcesso($nivelAcesso)
{
    if ($nivelAcesso == "admin") {
        return "gerente";
    }

    return $nivelAcesso;
}

?>

<?php
namespace cadastroTarefas\helpers;
class TarefasHelpers {

    public static function calculaHorasGasta(string $horaInicio, string $horaFim):string
    {
        $total = strtotime($horaFim) - strtotime($horaInicio);
        $horas = floor($total/60/60);
        $minutos = round(($total-($horas*60*60))/60);
        return $horas.":".$minutos;
    }
}

?>
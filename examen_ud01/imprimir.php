<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabla</title>
</head>
<body>

<?php
include 'datos.php';
global $conceptos;
?>
<table border="1">
    <tr>
        <td></td>
        <td><b>UDS.</b></td>
        <td><b>Referencia</b></td>
        <td><b>Concepto</b></td>
        <td><b>Precio ud.</b></td>
        <td><b>Subtotal</b></td>
    </tr>
    <?php
    $contConcepto=1;
    $contUnidaddes=0;
    $contBruto=0;
    $descuento=0;
    $iva=0;
    $neto=0;
    foreach ($conceptos as $concepto) {
        print "<tr>";
        print "<td align='center'>" .$contConcepto++.  "</td>";
        print "<td align='center'>" . $concepto['unidades'] . "</td>";
        print "<td align='center'>" . $concepto['referencia'] . "</td>";
        print "<td align='center'>" . $concepto['concepto'] . "</td>";
        print "<td align='right'>" . $concepto['precio_unidad']." $" . "</td>";
        print "<td align='right'>" . $concepto['unidades']*$concepto['precio_unidad']." $" . "</td>";
        print "</tr>";
        $contUnidaddes+= $concepto['unidades'];
        $contBruto+= $concepto['unidades']*$concepto['precio_unidad'];
    }
    print "<tr>";
    print "<td>".""."</td>";
    print "<td  align='center'>".$contUnidaddes."</td>";
    print "<td colspan='3' align='right'>"."Bruto:"."</td>";
    print "<td  align='right'>".$contBruto." $"."</td>";
    print "</tr>";
    print "<tr>";
    if ($contBruto>=2000 && $contBruto<=3000){
        $descuento=$contBruto*10/100;
        print "<td colspan='5'  align='right'>"."Descuento (10%):"."</td>";
        print "<td  align='right'>"."-".$descuento." $"."</td>";
    }
    if ($contBruto>3000){
        $descuento=$contBruto*10/100;
        print "<td colspan='5'  align='right'>"."Descuento (20%):"."</td>";
        print "<td  align='right'>"."-".$descuento." $"."</td>";
    }
    $contBruto=$contBruto-$descuento;
    $iva=$contBruto*21/100;
    print "</tr>";
    print "<tr>";

    print "<td colspan='5'  align='right'>"."IVA:"."</td>";
    print "<td  align='right'>".$iva." $"."</td>";

    print "</tr>";
    print "<tr>";
    $neto=$contBruto+$iva;
    print "<td colspan='5'  align='right'>"."Neto:"."</td>";
    print "<td  align='right'>".$neto." $"."</td>";

    print "</tr>";
    ?>
</table>

</body>
</html>

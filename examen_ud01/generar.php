<?php
include 'datos.php';
global $conceptos;
session_name("ud1_22");
session_start();

// Inicializar sesión si no existe
if (!isset($_SESSION['albaranArray'])) {
    $_SESSION['albaranArray']=[];
    $_SESSION['numero_version'] = 1;
}

//iniciamos el mensaje de error
$error = "";

if (isset($_POST['referencia']) &&  isset($_POST['concepto']) &&  isset($_POST['unidades']) &&  isset($_POST['precio_unidad'])) {
    $referencia = $_POST['referencia'];
    $concepto = $_POST['concepto'];
    $unidades = (int)$_POST['unidades'];
    $precio_unidad = (double)$_POST['precio_unidad'];


    //comprobamos que todo este bien y lo metemos en el array, si no lanzamos un error
    if (strlen($referencia) > 0 &&
        strlen($concepto) > 0 &&
        $unidades>0 &&
        $precio_unidad>=0) {

        $_SESSION['albaranArray'][] = [
            'referencia' => $referencia,
            'concepto' => $concepto,
            'unidades' => $unidades,
            'precio_unidad' => $precio_unidad
        ];
        $referencia = "";
        $concepto = "";
        $unidades = "";
        $precio_unidad = "";

        $_SESSION['numero_version']++;
    }
    else{
        $error="Hay algun error el los datos introducidos, vuelve a intentarlo";
        print ($error);
    }
}

if (isset($_POST["limpiar_albaran"])) {
    $_SESSION = array();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Botones + y -
if (isset($_POST['bmas']) || isset($_POST['bmenos'])) {
    $refe = isset($_POST['bmas']) ? $_POST['bmas'] : $_POST['bmenos'];
    for ($i = 0; $i < count($_SESSION['albaranArray']); $i++) {
        if ($_SESSION['albaranArray'][$i]['referencia'] === $refe) {
            if (isset($_POST['bmas'])) {
                $_SESSION['albaranArray'][$i]['unidades']++;
                $_SESSION['nueva_version']++;
            }
            if (isset($_POST['bmenos']) && $_SESSION['albaranArray'][$i]['unidades'] > 1) {
                $_SESSION['albaranArray'][$i]['unidades']--;
                $_SESSION['nueva_version']++;
            }
            break;
        }
    }
}

// Eliminar concepto
if (isset($_POST['belimi'])) {
    $concep = $_POST['belimi'];
    for ($i = 0; $i < count($_SESSION['albaranArray']); $i++) {
        if ($_SESSION['albaranArray'][$i]['concepto'] == $concep) {
            unset($_SESSION['albaranArray'][$i]);
            $_SESSION['numero_version']++;
            break;
        }
    }
}


print $_SESSION['numero_version'];
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabla Albarán</title>
</head>
<body>
<h1>Albarán</h1>
<table border="1">
    <tr>
        <td></td>
        <td><b>UDS.</b></td>
        <td><b>Referencia</b></td>
        <td><b>Concepto</b></td>
        <td><b>Precio ud.</b></td>
        <td><b>Subtotal</b></td>
        <td></td>
    </tr>
    <?php
    //hacemos lo necesario para tener la tabla completa
    $contConcepto=1;
    $contUnidaddes=0;
    $contBruto=0;
    $descuento=0;
    $iva=0;
    $neto=0;
    //si no hay nada en el array no muestra la tabla
    if (sizeof($_SESSION['albaranArray'])>0) {
        foreach ($_SESSION['albaranArray'] as $concepto) {
            print "<tr>";
            print "<td align='center'>" .$contConcepto++.  "</td>";
            print "<td align='center'><button type='submit' name='bmenos' value='{$concepto['referencia']}'>-</button>" . $concepto['unidades'] . "<button type='submit' name='bmas' value='{$concepto['referencia']}'>+</button></td>";
            print "<td align='center'>" . $concepto['referencia'] . "</td>";
            print "<td align='center'>" . $concepto['concepto'] . "</td>";
            print "<td align='right'>" . $concepto['precio_unidad']." $" . "</td>";
            print "<td align='right'>" . $concepto['unidades']*$concepto['precio_unidad']." $" . "</td>";
            print "<td align='center'>
            <button type='submit' name='belimi' value='{$concepto['concepto']}'>Eliminar</button>
            </td>";
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
    }
    ?>
</table>
</br>
</br>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label >Referencia: </label>
    <input type="text" name="referencia">

    <label >Concepto: </label>
    <input type="text" name="concepto">
    </br>
    </br>
    <label >Unidades: </label>
    <input type="text" name="unidades">

    <label >Precio Unidad: </label>
    <input type="text" name="precio_unidad">
    </br>
    </br>
    <input type="submit" name="nuevo_concepto" value="Nuevo Concepto">
    <input type="submit" name="limpiar_albaran" value="Limpiar Albarán">
</form>

</body>
</html>

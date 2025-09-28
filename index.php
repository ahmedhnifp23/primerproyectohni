<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas de Multiplicar</title>
    <style>
        table,
        th,
        td {
            border: 2px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: gray;
        }

        td:nth-child(1) {
            background-color: gray;
        }
    </style>
</head>

<body>
    <h1>Tabla de multiplicar del 1 al 10</h1>
</body>

</html>



<?php


$maxTabla = 10;

echo "<table>";
echo "<tr>";
for ($i = 1; $i <= $maxTabla; $i++) {
    echo "<th>$i</th>";
}
echo "</tr>";

for ($x = 1; $x <= $maxTabla; $x++) {
    echo "<tr>";
    for ($y = 1; $y <= $maxTabla; $y++) {
        $resultado = $x * $y;
        echo "<td>$resultado</td>";

    }
    echo "</tr>";
}
echo "</table>";






?>
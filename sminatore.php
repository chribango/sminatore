
<?php

session_start();

/* --- RESET DEL GIOCO --- */
if (isset($_POST['nuovo'])) {
    session_destroy();
    header("Location: sminatore.php");
    exit();
}

/* --- Se non esistono ancora numeri, crea nuova partita --- */
if (!isset($_SESSION['numeri_estratti'])) {
    $_SESSION['numeri_estratti'] = estrai();
    $_SESSION['tentativi'] = 0;
}

/* --- Gestione dato inserito --- */
$dato = null;
if (isset($_POST['dato']) && is_numeric($_POST['dato'])) {
    $dato = intval($_POST['dato']); // controllo sul tipo
    if($dato!=1) {
        divisibile($dato);          // elimina numeri divisibili
        $_SESSION['tentativi']++;    // conta tentativi
    }
}

/* --- FUNZIONE estrai --- */
function estrai(){
    $primi=[2,3,5,7,11,13];
    $numeri_estratti = [];

    for ($i=0; $i<15; $i++){
        $moltiplica =
            $primi[rand(0, count($primi)-1)] *
            $primi[rand(0, count($primi)-1)] *
            $primi[rand(0, count($primi)-1)];

        $moltiplica *= rand(1, 20);   // non usare 0, altrimenti genera sempre 0
        $numeri_estratti[] = $moltiplica;
    }

    return $numeri_estratti;
}

/* --- FUNZIONE stampa --- */
function stampa($stampare){
    if (!is_array($stampare)) return;
    foreach ($stampare as $v) {
        echo " § ".$v." <br>";
    }
}

/* --- FUNZIONE divisibile --- */
function divisibile($dato){
    foreach ($_SESSION['numeri_estratti'] as $k => $v) {
        if ($v != 0 && $v % $dato == 0) {
            unset($_SESSION['numeri_estratti'][$k]);
        }
    }
}
?>

<!-- ======================= HTML ======================= -->

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sminatore Matematico</title>
</head>
<body>

<h2>Sminatore Matematico</h2>

<form action="sminatore.php" method="post">

    <h3>Numeri ancora presenti:</h3>
    <?php stampa($_SESSION['numeri_estratti']); ?>

    <br><br>
    <input type="text" name="dato" placeholder="Inserisci un divisore">
    <input type="submit" value="Continua">

    <button type="submit" name="nuovo">Nuovo</button>

</form>

<br>

<!-- Contatore tentativi -->
Tentativi effettuati: <b><?php echo $_SESSION['tentativi']; ?></b>

<br><br>

<!-- Messaggio di vittoria -->
<?php
if (empty($_SESSION['numeri_estratti'])) {
    echo "<h2> Hai vinto! Tutti i numeri sono stati eliminati.</h2>";
    echo "<p>Mosse totali: <b>".$_SESSION['tentativi']."</b></p>";
}
?>

</body>
</html>

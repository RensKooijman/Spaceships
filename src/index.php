<?php
include_once 'Spaceship.php'
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spaceboot</title>
</head>

<body style="background-color: #383838;">
    <p style="font-family: Cambria; font-size: 20px; color:#8a2be2;">
        <?php
        $random_number = mt_rand(1, 1);
        $ship1 = new Spaceship;
        $ship2 = new Fightership("Fightership",120,120,123,array(12,12));
        $ship3 = new Healer(99,112,122,array(12,100));
        $ship4 = new Carriership("Carriership",98,210,121,array(12,25));
        $fleet = array($ship1,$ship2,$ship3,$ship4);


        $teamBlue=null;
        $teamRed=null;

        for ($i =$random_number; $i >= 0; $i--) {
            $randomIndex = rand(0, count($fleet) - 1);
            $selectedValue = $fleet[$randomIndex];
            $teamBlue[] = $selectedValue;
            echo $selectedValue->getName() . "<br>";
        }
        echo  "<br>";
        for ($i =$random_number; $i >= 0; $i--) {
            $randomIndex = rand(0, count($fleet) - 1);
            $selectedValue = $fleet[$randomIndex];
            $teamRed[] = $selectedValue;
            echo $selectedValue->getName() . "<br>";
        }
        $battle = new Battle("",$teamRed, $teamBlue);
        $battle->battle1();

        while ($battle->get_win() != "blue" && $battle->get_win() != "red") {
            $battle->battle1();
        }
        echo "The winner is: " . $battle->get_win() . "<br>";
        echo "The battle has ended.<br>";

        foreach($fleet as $ship){
            foreach($ship as $info){
                if(!is_array($info)){
                echo "$info <br>";
                }
                else{
                    foreach($info as $inf){
                        echo "$inf <br>";
                    }
                }
            }
        }
        var_export($battle->getTeamRed(), true);
        var_export($battle->getTeamBlue(), true);
        echo "The end of the code has been reached.<br>";
        ?>
    </p>
</body>
</html>
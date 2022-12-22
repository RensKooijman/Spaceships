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
        $random_number = mt_rand(1, 10);
        $ship1 = new Spaceship;
        $ship2 = new Fightership("Fightership",120,120,123,array(12,12));
        $ship3 = new Healer(99,112,122,array(12,100));
        $ship4 = new Carriership("Carriership",98,210,121,array(12,25));
        $fleet = array($ship1,$ship2,$ship3,$ship4);
        $battle = new battle;


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
        $battle->battle1($teamRed,$teamBlue);

        foreach($battle as $info){
                echo $info;
        }

        echo "ship1 ammo: " . $ship1->getAmmo() . "<br>";
        echo "ship1 HP: " . $ship1->getHitPoints() . "<br>";
        echo "ship1 fuel: " . $ship1->getfuel() . "<br>";
        echo "<br>";

        echo "Ship2 ammo: " . $ship2->getAmmo() . "<br>";
        echo "Ship2 HP: " . $ship2->getHitPoints() . "<br>";
        echo "Ship2 boost: " . $ship2->getboost() . "<br>";
        echo "Ship2 fuel: " . $ship2->getfuel() . "<br>";
        echo "<br>";

        echo "ship3 ammo: " . $ship3->getAmmo() . "<br>";
        echo "ship3 HP: " . $ship3->getHitPoints() . "<br>";
        echo "ship3 barrier: ". $ship3->getbarrier(). "<br>";
        echo "ship3 Healing: ". $ship3->getHitpointsInTank(). "<br>";
        echo "ship3 fuel: " . $ship3->getfuel() . "<br>";
        echo "<br>";

        echo "ship4 ammo: " . $ship4->getAmmo() . "<br>";
        echo "ship4 HP: " . $ship4->getHitPoints() . "<br>";
        echo "ship4 giveable fuel: ". $ship4->getFuelInTank(). "<br>";
        echo "ship4 fuel: " . $ship4->getfuel() . "<br>";
        echo "<br>";

        echo "Ship2 fuel: " . $ship2->getfuel() . "<br>";

        //test shoot
        $dmg = $ship1->shoot();
        $ship2->hit($dmg);
        echo"shot <br>";
        echo "<br>";

        //test barrier
        $dmg = $ship2->shoot();
        $ship3->hit($dmg);
        echo "shot <br>";
        $ship3->getbarrier();
        echo "ship3 barrier: ". $ship3->getbarrier(). "<br>";
        echo "ship3 HP: " . $ship3->getHitPoints() . "<br>";
        echo "<br>";

        // Aantonen dat het is gelukt.
        echo "Ship1 ammo: " . $ship1->getAmmo() . "<br>";
        echo "Ship2 ammo: " . $ship2->getAmmo() . "<br>";
        echo "Ship1 HP: " . $ship1->getHitPoints() . "<br>" ;
        echo "Ship2 HP: " . $ship2->getHitPoints() . "<br>";
        echo "<br>";

        //test move
        $ship2->move(20,10);
        echo "<br>";
        echo "Ship2 fuel: " . $ship2->getfuel() . "<br>";
        
        //test give feul
        $ship4->giveFuel();
        $ship2->getextraFuel();


        echo "ship4 giveable fuel: ". $ship4->getFuelInTank(). "<br>";
        echo "Ship2 fuel: " . $ship2->getfuel() . "<br>";
        echo "<br>";

        //test heal
        $ship3->giveheal();
        $ship2->getextrahitpoints();


        echo "ship3 giveable health: ". $ship3->getHitpointsInTank(). "<br>";
        echo "Ship2 hp: " . $ship2->gethitpoints() . "<br>";
        echo "<br>";
        

        //test boost
        $ship2->boost();
        echo "Ship2 boost: " . $ship2->getboost() . "<br>";
        echo "<br>";

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
        echo "The end of the code has been reached.<br>";
        ?>
    </p>
</body>
</html>
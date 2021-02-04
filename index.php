<?php include("head2.php");?>
<link rel="stylesheet" href="calendar.css">
<h2>Partie 8 - Exercice 9</h2>
<p>
    Faire un formulaire avec deux listes déroulantes. La première sert à choisir le mois, et le deuxième permet d'avoir l'année.<br>
    En fonction des choix, afficher un calendrier comme celui-ci : 
</p>
<?php
    // Création des listes déroulantes.
    $tabMonth = [   1 => 'Janvier',
                    2 => 'Février',
                    3 => 'Mars',
                    4 => 'Avril',
                    5 => 'Mai',
                    6 => 'Juin',
                    7 => 'Juillet',
                    8 => 'Août',
                    9 => 'Septembre',
                    10 => 'Octobre',
                    11 => 'Novembre',
                    12 => 'Décembre'
                ];
    
    $firstYear = 1970;
    $lastYear = 2037;
?>
    <form method="get" id="formCalendar">
        <label for="month">Mois</label>
        <select name ="month" id="month">
            <?php 
                foreach($tabMonth as $key => $value){
                
                    if(isset($_GET['month']) && $_GET['month'] == $key){
                        ?>
                            <option value="<?=$key?>" selected><?=$value?></option>
                        <?php
                    }elseif(!isset($_GET['month']) && $key == date('n')){
                        ?>
                            <option value="<?=$key?>" selected><?=$value?></option>
                        <?php
                    }else{
                        ?>
                            <option value="<?=$key?>"><?=$value?></option>
                        <?php
                    }
                }
            ?>
        </select><br>
        <label for="year">Année</label>
        <select name ="year" id="year">';
            <?php 
                for($ye = $lastYear; $ye >= $firstYear; $ye--){
                    
                    if(isset($_GET['year']) && $_GET['year'] == $ye){
                        ?>
                        <option value="<?=$ye?>" selected><?=$ye?></option>
                        <?php
                    }elseif($ye == date('Y') && !isset($_GET['year'])){
                        ?>
                        <option value="<?=$ye?>" selected><?=$ye?></option>
                        <?php
                    }else{
                        ?>
                        <option value="<?=$ye?>"><?=$ye?></option>
                        <?php
                    }
                }
            ?>
        </select><br>
        <input type="submit" value="Valider"><br>
    </form>
<?php

    // A REVOIR DANS UNE V.50 POUR AFFICHER EN JS ET CONTROLER EN PHP Bon chance !
            
    //Test si les variables globales ont été remplis.
    if(isset($_GET['month']) && isset($_GET['year'])){
        $month = $_GET['month'];
        $year = $_GET['year'];
       
        if(isset($_GET['prevMonth'])){
            $monthPrev = $month;
            $yearPrev = $year;
             if($month == 1){
                 $monthPrev = 12;
                $yearPrev--;
            }else{
                 $monthPrev--;
            }
            echo calendar($monthPrev, $yearPrev);
        }elseif(isset($_GET['nextMonth'])){
            $monthNext = $month;
            $yearNext = $year;
            if($month == 12){
                $monthNext = 1;
                $yearNext++;
            }else{
                $monthNext++;
            }
            echo calendar($monthNext, $yearNext);
        }else{
            echo calendar($month, $year);
        }
    } 

    function calendar($month, $year){

        $sem = array(6,0,1,2,3,4,5); //Tableau des jours 0 = lundi et 6 = dimanche.
        $semaine = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');//Tableau des jours en string.

        //Test si l'utilisateur a bien saisi un mois et une année correct.
        if($month == 0 || $year == 0){
            echo 'Veuillez saisir un mois et une année correct.';
            exit;
        }else{//sinon affiche le calendrier.
            //Récupère le timestamp du mois et de l'année saisi.
            $tim = mktime(0, 0, 0, $month, 1, $year);
            //Format date francaise.
            //setlocale(LC_TIME, 'fr_FR.UTF8','fra');
            setlocale(LC_TIME, "fra.UTF8");
            //utf8_encode() force l'encodage en utf-8
        ?>
            <div class="table-responsive">
                <table class="table table-bordered caption-top tabCal mx-auto w-75">
                    <caption class="text-uppercase text-center">
                        <button type="submit" name="prevMonth" form="formCalendar"><i class="bi bi-arrow-left"></i></button>
                        <?= strftime('%B', $tim). ' '. $year ?>
                        <button type="submit" name="nextMonth" form="formCalendar"><i class="bi bi-arrow-right"></i></button>
                    </caption>
                    <tr>
        <?php
            //boucle les jours de la semaine dans l'entête du calendrier. Lundi, Mardi,...
            for($i=0;$i<7;$i++){
                ?>
                    <td class="bg-secondary text-white fw-bold"><?=$semaine[$i]?></td>
                <?php
            }
            ?>
                </tr>
                    <tbody>
            <?php
            //boucle le nombre de ligne du tableau calendrier
            $firstDayInMonth = date('w',mktime(0,0,0,$month,1,$year));

            if($firstDayInMonth <= 5 && $firstDayInMonth > 0 ){
                $nbL = 5;
            }else{
                $nbL = 6;
            }
            //on peut faire un while days <= totaldayinmonth
            for($line=0;$line<$nbL;$line++){
                echo '<tr>';
                //boucle pour remplir les cases du tableau 
                for($j=0;$j<7;$j++){
                    $w = $sem[date('w',$tim)]; // récupère le jours (1,2) en fonction du timestamp
                    $month2 = date('n',$tim); // récupère le mois (1,2,3) en fonction du timestamp
                    //test si $j de la boucle est = au jour récupère et pareil pour le mois
                    if($w == $j && $month2 == $month){
                        $today = date('Y/n/j', $tim);
                        // si la date est la date courante change la couleur du jours et rempli le tableau
                        if(date('j, n, Y',$tim) == date('j, n, Y')){
                            ?>
                                <td class="text-start align-top text-danger fw-bold tdRempli"><?=date('j',$tim)?></td>  
                            <?php
                        }elseif(daysOff($today, $year)){
                            ?>
                                <td class="text-start align-top text-success fw-bold tdRempli"><?=date('j',$tim)?></td> 
                            <?php
                        }else{
                            ?>
                                <td class="text-start align-top tdRempli"><?=date('j',$tim)?></td>
                            <?php
                        }
                        // jours suivant, 86400 = nb seconde d'une journée.
                        $tim += 86400;
                    }else{
                        ?>
                            <td class="table-secondary"></td>
                        <?php
                    }
                }
                ?>
                    </tr>
                <?php
            }
            ?>
                </tbody></table></div>
            <?php
        }
    }
    ?>
    
    <?php

    function daysOff($today, $y1){

        //fêtes fixes
        $tabDaysOff = [
            1 => $y1 .'/1/1',
            2 => $y1 .'/5/1',
            3 => $y1 .'/5/8',
            4 => $y1 .'/7/14',
            5 => $y1 .'/8/15',
            6 => $y1 .'/11/1',
            7 => $y1 .'/11/11',
            8 => $y1 .'/12/25'
        ];
        //Parcours le tableau des jours fériés et retourne true si le jours courant est dans le tableau.
        foreach($tabDaysOff as $k => $val){
            if($tabDaysOff[$k] == $today){
               return true;
            }            
        } 

        // fêtes mobiles

        //Retourne le nombre de jours entre le 21 mars et le jour de paques
        $easter =  easter_days($y1);
        
        //crée le timestamp du 21 mars selon l'année saisie
        $march21 = strtotime($y1.'/03/21');
        
        //création d'objet DateTime avec la date du 21 mars en format annné/mars/21
        $date = new DateTime(date('Y/n/j', $march21));
        //ajoute à l'objet le nombre de jours qui sépare le 21 mars et paques.
        $date->add(new DateInterval('P'.$easter.'D'));
        
        //Retourne le jours de paques
        if($today == $date->format('Y/n/j')){
            return true;
        }

        $easterMonday = $date->add(new DateInterval('P1D'));
        if($today == $easterMonday->format('Y/n/j')){
            return true;
        }

        $ascension = $easterMonday->add(new DateInterval('P38D'));
        if($today == $ascension->format('Y/n/j')){
            return true;
        }

        $pentecote = $ascension->add(new DateInterval('P10D'));
        if($today == $pentecote->format('Y/n/j')){
            return true;
        }

        $pentecoteMonday = $pentecote->add(new DateInterval('P1D'));
        if($today == $pentecoteMonday->format('Y/n/j')){
            return true;
        }
    }
    include("foot.php");
?>
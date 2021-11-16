<?php

use PcBuilder\Objects\Component;
include 'templates/Header.php';



?>
<link type="text/css" href="/assets/css/configrator.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
            echo "<h2>$name</h2>";
            echo "<p>$description</p>";
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-10"><div style="margin-left: 10px;" id="builder" class="mainCard">
                <?php
                function renderData($title,$id,$data,$description){
                    ?>
                    <div id="<?php echo $id?>" >
                        <h3><?php echo $title?></h3>
                        <p><?php echo $description?></p>
                        <div class="card-group">
                            <?php

                            foreach ($data as $case){
                                /**
                                 * @var Component
                                 */
                                $ca = $case;
                                ?>
                                <div id="<?php echo $id.$ca->getId() ?>"  data-category="<?php echo $id?>" data-price="<?php echo $ca->getPrice() ?>" data-id="<?php echo $ca->getId() ?>" class="card mb-3" style="max-width: 300px; margin-right: 5px;border:1px solid;margin-bottom: 0px !important;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?php echo $ca->getImage()?>" class="img-fluid rounded-start" alt="<?php echo $ca->getDisplayName()?>">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $ca->getDisplayName()?></h5>
                                                <p class="card-text"><button data-bs-toggle="modal" data-bs-target="#modal-<?php echo "case-".$ca->getId() ?>"><i class="fas fa-info-circle"></i></button></p>
                                                <p class="card-text"><small class="text-muted">+$<?php echo $ca->getPrice()?></small></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-<?php echo "case-".$ca->getId() ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel"><?php echo $ca->getDisplayName()?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo $ca->getDescription()?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                }

                renderData("Behuizing","case",$cases,"In de behuizing gaan alle onderdelen");
                renderData("Proccesor","cpu",$cpu,"De cpu is het brein van je computer");
                renderData("Moederbord","motherboard",$motherboard,"Op het moederbord worden alle onderdelen op aangesloten");
                renderData("Geheugen","memory",$memory,"Het geheugen is het korte termijn geheugen van je pc");
                renderData("Opslag","storage",$storage,"De opslag van je pc is om alles op te slaan");
                renderData("Videokaart","dvds",$gpu,"Een dvd-speler is een apparaat voor het afspelen van dvd's waarop beeld en/of muziek in digitale vorm is opgeslagen.");
                renderData("Rgb","rgb",$rgb,"Laat jou pc nu shinen met RGB!");
                renderData("Dvd speler","dvds",$dvd,"Een dvd-speler is een apparaat voor het afspelen van dvd's waarop beeld en/of muziek in digitale vorm is opgeslagen.");
                renderData("Voeding","psu",$psu,"De voeding geeft stroom aan je pc deze moet niet te weinig geven");
                renderData("Os","os",$os,"Laat jou pc nu shinen met RGB!");


                ?>

            </div></div>
        <div class="col-2"><div id="sideBar" class="sideBar">
                <div id="priceBox">
                    <p id="finalPrice"></p>
                    <form method="post">
                        <input type="hidden" name="pcName" id="pcName" value="<?php echo $name?>">
                        <input type="hidden" name="config" id="config" value="">
                        <input type="submit" name="addToCard"
                               value="addToCard"/>
                    </form>
                </div>
            </div></div>
    </div>
</div>



<script src="/assets/js/configrator.js"></script>
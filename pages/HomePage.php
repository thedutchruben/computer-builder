<?php

include 'templates/Header.php';

?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="base-text">
                <h2 class="header">Welkom op pc builder site hier kan je je computer samenstellen</h2>
                <p>Op deze site kan je je droom computer zo samenstellen als je wil!</p>
                <p>Kies hier beneden voor een samenstelling die je wil maken</p>
                <div class="card-group" id="configs">
                    <?php
                    foreach ($configs as $conf){
                        ?>
                        <div class="card mb-3" style="max-width: 450px;margin-left: 10px">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="<?php echo $conf['image']?>" class="img-fluid rounded-start" alt="Computer image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $conf['name']?></h5>
                                        <p>Start vanaf €<?php echo $conf['price']?></p>
                                        <a href="/custom-pc/<?php echo $conf['id']?>" class="card-link">Open</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

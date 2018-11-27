<?php
return function(\Elusim\Components $components, $params = array()) {
    $validator = new \Validate();

    $creator = $components->get("respose.offer.creator");
    $creator = $components->get("respose.offer.activator");
    $creator = $components->get("respose.offer.logger");

    $components->get("\App\Models\Offers\Offer");
    $components->get("\App\Models\Offers\Activator");
    $components->get("\App\Models\Offers\Remover");
    $components->get("\App\Models\Offers\Updater");

    components :
        \App\Models\Offers\Offer :
            alias : offers
            arguments :
                logger : 'logger',
                mail : 'logger',
                logger : 'logger',
                logger : 'logger',


    $components->get("offers");

    $offer = $components->get("offer", array(
        "id" => 12,
    ));

    $creator->validate();
};

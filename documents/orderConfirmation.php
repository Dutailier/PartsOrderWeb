<?php

/**
 * Concatonne les détails de l'adresse en une seule chaîne de caractères.
 * @param array $address
 * @return string
 */
function addressFormat(array $address)
{
    return
        $address['details'] . ', ' .
        $address['city'] . ', ' .
        $address['zip'] . ', ' .
        $address['state']['name'];
}

/**
 * Transforme 12345678901 pour 1-234-567-8910.
 * @param $phone
 * @return string
 */
function phoneFormat($phone)
{
    return
        substr($phone, 0, 1) . '-' .
        substr($phone, 1, 3) . '-' .
        substr($phone, 4, 3) . '-' .
        substr($phone, 7);
}

/**
 * Transforme 2013-06-07 10:24:15.227 pour 2013-06-07 10:24.
 * @param $date
 * @return string
 */
function dateFormat($date)
{
    return substr($date, 0, 16);
}
?>

<html>
<body>
<div style="width: 960px; margin: 0px auto; overflow: hidden;">
    <fieldset style="width: 898px; height: 75px; display: block; float: left; margin: 10px 10px 20px 10px; padding: 10px 20px; border: 1px solid #484848; overflow: hidden;">
        <legend style="padding: 0px 10px; font-size: 1.2em; font-weight: bold;">Order Informations</legend>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Number : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $order['number']; ?></label>
        </p>

        <p style="padding: 0px 62px; float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Creation date : </label>
            <label style="display: block; float: left; clear: both;"><?php echo dateFormat($order['creationDate']); ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Status : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $order['status']; ?></label>
        </p>
    </fieldset>
    <fieldset style="display: block; float: left; margin: 10px 10px 20px 10px; padding: 10px 20px; width: 258px; height: 250px; border: 1px solid #484848; overflow: hidden;">
        <legend style="padding: 0px 10px; font-size: 1.2em; font-weight: bold;">Store informations</legend>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Name : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $store['name']; ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Phone : </label>
            <label style="display: block; float: left; clear: both;"><?php echo phoneFormat($store['phone']); ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Email : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $store['email']; ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Address : </label>
            <label style="display: block; float: left; clear: both;"><?php echo addressFormat($store['address']); ?></label>
        </p>
    </fieldset>
    <fieldset style="display: block; float: left; margin: 10px 10px 20px 10px; padding: 10px 20px; width: 258px; height: 250px; border: 1px solid #484848; overflow: hidden;">
        <legend style="padding: 0px 10px; font-size: 1.2em; font-weight: bold;">Receiver informations</legend>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Name : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $receiver['name']; ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Phone : </label>
            <label style="display: block; float: left; clear: both;"><?php echo phoneFormat($receiver['phone']); ?></label>
        </p>

        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Email : </label>
            <label style="display: block; float: left; clear: both;"><?php echo $receiver['email']; ?></label>
        </p>
    </fieldset>
    <fieldset style="display: block; float: left; margin: 10px 10px 20px 10px; padding: 10px 20px; width: 258px; height: 250px; border: 1px solid #484848; overflow: hidden;">
        <legend style="padding: 0px 10px; font-size: 1.2em; font-weight: bold;">Shipping informations</legend>
        <p style="float: left; width: 258px; margin: 5px 0px; clear: both; overflow: hidden;">
            <label style="display: block; float: left; font-weight: bold;">Address : </label>
            <label style="display: block; float: left; clear: both;"><?php echo addressFormat($shippingAddress); ?></label>
        </p>
    </fieldset>
    &nbsp;
    <hr/>

    <div style="margin: 10px auto; overflow: hidden;">
        <?php foreach ($lines as $line) { ?>
            <?php $product = $line['product']; ?>
            <div style="margin: 5px 10px;">
                <div style="padding: 5px; border: 1px solid #484848; margin-top: 15px; height: 40px;">
                    <label style="font-size: 2.5em; display: inline-block; line-height: 40px; margin: 0px 5px; float: left"><?php echo $line['quantity']; ?></label>
                    <label style="font-weight: bold; font-size: 1.2em; display: inline-block; line-height: 40px; margin: 0px 5px; float: left"><?php echo $product['name']; ?></label>
                    <label style="font-size: 0.8em; font-style: italic;display: inline-block; line-height: 40px; margin: 0px 5px; float: left"><?php echo $line['serial']; ?></label>
                </div>
                <div style="position: relative; border: 1px solid #484848; border-top: 0px solid #FFFFFF; padding: 10px 20px; margin-bottom: 10px; overflow: hidden;">
                    <p style="float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
                        <label style="display: block; float: left; font-weight: bold;">Sku : </label>
                        <label style="display: block; float: left; clear: both;"><?php echo $line['sku']; ?></label>
                    </p>

                    <p style="padding: 0px 62px; float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
                        <label style="display: block; float: left; font-weight: bold;">Frame : </label>
                        <label style="display: block; float: left; clear: both;"><?php echo $line['frame']; ?></label>
                    </p>

                    <p style="float: left; width: 258px; margin: 5px 0px; clear: none; overflow: hidden;">
                        <label style="display: block; float: left; font-weight: bold;">Cushion : </label>
                        <label style="display: block; float: left; clear: both;"><?php echo $line['cushion']; ?></label>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>

    <hr/>

    <div style="margin: 10px auto; overflow: hidden;">
        <?php foreach ($comments as $comment) { ?>
            <?php $user = $comment['user']; ?>
            <div style="position: relative; border: 1px dotted #484848; padding: 10px; margin: 10px 10px; overflow: hidden;">
                <label style="font-size: 1.2em; line-height: 30px;"><?php echo $comment['text']; ?></label>

                <div style="position: absolute; bottom: 0; right: 0; margin: 10px; clear: both; font-size: 0.7em; float: right;">
                    By <label style="font-weight: bold;"><?php echo $user['username']; ?></label>
                    at <label style="font-style: italic;"><?php echo dateFormat($comment['creationDate']); ?></label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>

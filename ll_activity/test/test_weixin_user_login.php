<?php
$request = array(
    't' => time(),
    'openid' => "oVLaCwYTqL3r_N-S5dlFr37hkdYA",
);

echo base64_encode(json_encode($request));
exit();

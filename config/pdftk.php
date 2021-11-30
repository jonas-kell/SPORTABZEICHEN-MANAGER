<?php

return [

    'configuration_array' => env("PDFTK_LIBRARY_PATH", null) != null ? array(
        // e.g. /project/pdftk/bin/pdftk
        'command' => env("PDFTK_COMMAND"),
        'procEnv' => array(
            // e.g. /project/pdftk/bin (should contain libgcj.so.10)
            'LD_LIBRARY_PATH' => env("PDFTK_LIBRARY_PATH")
        )
    ) : array('command' => env("PDFTK_COMMAND"))


];

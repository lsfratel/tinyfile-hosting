<?php

// This file gets included at the end of the application boot sequence

$errorHandler = $container->get('errorHandler');

$errorHandler->disableLoggingFor([
    'mako\http\exceptions\NotFoundException',
]);

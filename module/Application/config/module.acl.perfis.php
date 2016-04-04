<?php

return array(
// Perfil de administrador
    array(
        'role' => 1,
        'resource' => 'Application\Controller\Usuario',
        'privileges' => array(
            'list',
            'add',
            'edit'
        ),
    ),
    array(
        'role' => 1,
        'resource' => 'Localizacao\Controller\Index',
        'privileges' => array(
            'index',
            'list',
            'add',
            'edit'
        ),
    ),
    array(
        'role' => 1,
        'resource' => 'Localizacao\Controller\Cidade',
        'privileges' => array(
            'index',
            'list',
            'add',
            'edit'
        ),
    ),
    array(
        'role' => 1,
        'resource' => 'Localizacao\Controller\Estado',
        'privileges' => array(
            'index',
            'list',
            'add',
            'edit'
        ),
    ),
    // Perfil de oreia
    array(
        'role' => 2,
        'resource' => 'Application\Controller\Usuario',
        'privileges' => array(
            'list'
        ),
    )
);

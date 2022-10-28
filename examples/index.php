<?php
/**
 * index.php
 * @author Lídmo <suporte@lidmo.com.br>
 */

require __DIR__ . '/../vendor/autoload.php';

try {

    // Autenticar-se
    $token = \Deltagestor\Sisgenfe\Sisgenfe::auth([
        'app' => '',
        'prestador' => '',
        'username' => '',
        'password' => ''
    ]);
    // dd($token);

    $sisgenfe = new \Deltagestor\Sisgenfe\Sisgenfe($token);

    // Buscar CNAEs do prestador
    dd($sisgenfe->getCnaes());
} catch (\Exception $e) {
    dd($e->getMessage());
}
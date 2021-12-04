<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PerfilArtistaController extends AbstractController
{
    /**
     * @Route("/perfil/artista", name="perfil_artista")
     */
    public function index(): Response
    {
        return $this->render('perfil_artista/perfil.html.twig', [
            'controller_name' => 'PerfilArtistaController',
        ]);
    }
}

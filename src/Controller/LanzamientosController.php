<?php

namespace App\Controller;

use App\Service\ConexionApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LanzamientosController extends AbstractController
{
    private $client;

    private $auth = "";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/lanzamientos", name="lanzamientos")
     */
    public function index(ConexionApi $conexionApi): Response
    {
        $this->auth = $conexionApi->getToken();

        $lanzamientos = $this->getLanzamientos();

        return $this->render('lanzamientos/lanzamientos.html.twig', [
            'controller_name' => 'LanzamientosController',
            'title' => 'Ultimos Lanzamientos',
            'lanzamientos' => $lanzamientos
        ]);
    }

    public function getLanzamientos()
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/new-releases?country=SE&limit=30&offset=5', [
            'headers' => [
                'Authorization' => $this->auth,
            ],
        ]);

        $content = $response->toArray();

        $news = $content['albums']['items'];

        return $news;
    }
}

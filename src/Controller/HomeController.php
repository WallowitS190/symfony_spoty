<?php

namespace App\Controller;

use App\Service\ConexionApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{

    private $client;

    private $auth = "";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ConexionApi $conexionApi): Response
    {
        $this->auth = $conexionApi->getToken();

        var_dump($this->auth);

        $artistas = $this->getArtista();
        $albunes = $this->getAlbum();


        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'titleArtista' => 'Artistas',
            'titleAlbum' => 'Album',
            'datos' => $artistas,
            'album' => $albunes
        ]);
    }

    public function getArtista()
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/artists?ids=2CIMQHirSU0MQqyYHq0eOx%2C57dN52uHvrHOxijzpIgu3E%2C1vCWHaC5f2uS3yhpwWbIA6', [
            'headers' => [
                'Authorization' => $this->auth,
            ],
        ]);

        //$content = $response->getContent();
        $content = $response->toArray();
        $artistas = array();
        foreach ($content as $val) {
            $artistas += $val;
        }

        return $artistas;
    }


    public function getAlbum()
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/albums?ids=382ObEPsp2rxGrnsizN5TX%2C1A2GTWGtFfWp7KSQTwWOyo%2C2noRn2Aes5aoNVsU6iWThc&market=ES', [
            'headers' => [
                'Authorization' => $this->auth,
            ],
        ]);

        $content = $response->toArray();
        $albunes = array();
        foreach ($content as $val) {
            $albunes += $val;
        }

        return $albunes;
    }

    /**
     * @Route("/artista/{id}", name="artista")
     */

    public function getArtistaById(ConexionApi $conexionApi, $id)
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/artists/' . $id, [
            'headers' => [
                'Authorization' => $conexionApi->getToken(),
            ],
        ]);

        $response2 = $this->client->request('GET', 'https://api.spotify.com/v1/artists/' . $id . '/top-tracks?market=ES', [
            'headers' => [
                'Authorization' => $conexionApi->getToken(),
            ],
        ]);

        $tracks = $response2->toArray();

        $perfil = $response->toArray();
        $foto = $perfil['images'][0]['url'];
        $name = $perfil['name'];

        $track = array();
        foreach ($tracks as $val) {
            $track += $val;
        }

        return $this->render('perfil_artista/perfil.html.twig', [
            'foto' => $foto,
            'nombre' => $name,
            'tracks' => $track,
        ]);
    }
}

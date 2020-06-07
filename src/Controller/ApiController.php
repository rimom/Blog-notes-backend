<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    private $postRepository;
    private $em;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
    }

    /**
     * @Route("/api", name="api", methods={"POST"})
     */
    public function index()
    {
        $soapServer = new \SoapServer(null, ['uri' => '/']);
        $soapServer->setObject(new $this($this->postRepository, $this->em));

        $response = new Response();
        ob_start();
        $soapServer->handle();
        $response->setContent(ob_get_clean());

        $response->setStatusCode(200)->isOk();

        return $response;
    }

    public function deannonymizer(string $txt, Object $ids): string
    {
        $ids = $ids->item;

        $csvFile = file(__DIR__.'/../data/names.csv');

        foreach ($ids as $key => $value){
            $span = rawurlencode("<span id=\"{$value}\">*****</span>");
            $txt = str_replace( $span, trim($csvFile[$value]), $txt);
        }

        return $txt;
    }

    public function findAll()
    {
        return $this->postRepository->findAll();
    }

    public function create(string $title, string $author, string $body)
    {
        if(!$title || !$author || !$body){
            return "All parameters needs to be provided";
        }

        $csvFile = file(__DIR__.'/../data/names.csv');
        $body = rawurldecode($body);
        foreach ($csvFile as $key => $name){
            $body = str_replace(trim($name), "<span id=\"{$key}\">*****</span>" ,$body);
        }
        $body = rawurlencode($body);

        $post = new Post();
        $post->setTitle($title);
        $post->setAuthor($author);
        $post->setBody($body);

        $this->em->persist($post);
        $this->em->flush();

        return $post->getBody();
    }

    public function delete(int $id)
    {
        $post = $this->postRepository->find($id);
        $this->em->remove($post);
        $this->em->flush();
        return "Deleted";
    }
}

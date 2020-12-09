<?php

namespace App\Controller;

use App\Entity\Server;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/server", name="server_")
 */
class ServerController extends AbstractController
{
    /**
     * @Route("/{name}", name="put", methods={"PUT"})
     */
    public function put(Request $request, string $name, ServerRepository $serverRepository, EntityManagerInterface $entityManager): Response
    {
        $server = $serverRepository->findOneByName($name);
        if (null === $server) {
            $server = new Server();
        }
        $server
            ->setName($name)
            ->setRawData((string) $request->getContent());
        $entityManager->persist($server);
        $entityManager->flush();

        return new Response('', Response::HTTP_OK);
    }
}

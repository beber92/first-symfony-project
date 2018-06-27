<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Match;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddMatchController extends Controller
{
    /**
     * @Route("/matches", name="add_match")
     */
    public function addMatch(Request $request, EntityManagerInterface $entityManager)
    {
      $request_body = json_decode($request->getContent());

      $match = new Match($request_body);

      $playerOne=$this->getDoctrine()
      ->getRepository(Player::class)
      ->find($request_body->players[0]);

      $playerTwo=$this->getDoctrine()
      ->getRepository(Player::class)
      ->find($request_body->players[1]);

      $playerOne->updateResultsPlayerOne($request_body->score);
      $playerTwo->updateResultsPlayerTwo($request_body->score);

      $entityManager->persist($match);
      $entityManager->persist($playerOne);
      $entityManager->persist($playerTwo);

      $entityManager->flush();

      $response = new JsonResponse(
        $match->matchToJson(),
        201,
        array('access-control-allow-origin' => '*')
      );

      return $response;
    }
}
<?php

namespace App\Controller;

use App\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class GroupeController extends AbstractController
{
  // private $groupePublishHandler;

  //   public function __construct(Groupe $groupePublishHandler)
  //   {
  //       $this->groupePublishHandler = $groupePublishHandler;
  //   }

  //   public function __invoke(Groupe $groupe): Groupe
  //   {
  //       $this->groupePublishHandler->setCreatedAt(new \DateTimeImmutable());

  //       return $groupe;
  //   }
}
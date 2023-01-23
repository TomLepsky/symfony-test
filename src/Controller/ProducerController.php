<?php

namespace App\Controller;

use App\Entity\Producer;
use App\Helper\ErrorHelper;
use App\Helper\ResponseBag;
use App\Repository\ProducerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/producer', condition: "request.headers.get('Content-Type') matches '~Application\/json~i'")]
class ProducerController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProducerRepository $producerRepository)
    {}

    #[Route(path: '', name: 'producer_get_collection', methods: ['GET'])]
    public function get() : ResponseBag
    {
        return new ResponseBag($this->producerRepository->findAll(), 200, ['producer']);
    }

    #[Route(path: '', name: 'producer_create', methods: ['POST'])]
    public function create(Request $request, ErrorHelper $errorHelper) : ResponseBag
    {
        $params = $request->toArray();
        $producer = new Producer();
        $producer->setName($params['name'] ?? null);
        $errors = $this->validator->validate($producer);
        if (count($errors) > 0) {
            return new ResponseBag($errorHelper->transformErrors($errors), 422);
        }

        $this->producerRepository->save($producer, true);

        return new ResponseBag($producer, 201, ['producer']);
    }
}

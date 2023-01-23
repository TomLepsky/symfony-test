<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Producer;
use App\Entity\ProductType;
use App\Helper\ErrorHelper;
use App\Helper\ResponseBag;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/model', condition: "request.headers.get('Content-Type') matches '~Application\/json~i'")]
class ModelController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface  $entityManager
    ) {}

    #[Route(path: '', name: 'model_get_collection', methods: ['GET'])]
    public function get() : ResponseBag
    {
        return new ResponseBag($this->entityManager->getRepository(Model::class)->findAll(), 200, ['model']);
    }

    #[Route(path: '', name: 'model_create', methods: ['POST'])]
    public function create(Request $request, ErrorHelper $errorHelper) : ResponseBag
    {
        $params = $request->toArray();
        $model = new Model();
        $model->setName($params['name'] ?? null);

        if (!empty($params['producer_id'])) {
            $producer = $this->entityManager->getRepository(Producer::class)->find($params['producer_id']);
            $model->setProducer($producer);
        }

        if (!empty($params['product_type_id'])) {
            $productType = $this->entityManager->getRepository(ProductType::class)->find($params['product_type_id']);
            $model->setProductType($productType);
        }

        $errors = $this->validator->validate($model);
        if (count($errors) > 0) {
            return new ResponseBag($errorHelper->transformErrors($errors), 422);
        }

        /** @var ModelRepository $repository */
        $repository = $this->entityManager->getRepository(Model::class);
        $repository->save($model, true);

        return new ResponseBag($model, 201, ['model']);
    }
}

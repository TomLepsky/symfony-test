<?php

namespace App\Controller;

use App\Entity\ProductType;
use App\Helper\ErrorHelper;
use App\Helper\ResponseBag;
use App\Repository\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/product-type', condition: "request.headers.get('Content-Type') matches '~Application\/json~i'")]
class ProductTypeController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ProductTypeRepository $productTypeRepository)
    {}

    #[Route(path: '', name: 'product_type_get_collection', methods: ['GET'])]
    public function get() : ResponseBag
    {
        return new ResponseBag($this->productTypeRepository->findAll(), 200, ['productType']);
    }

    #[Route(path: '', name: 'product_type_create', methods: ['POST'])]
    public function create(Request $request, ErrorHelper $errorHelper) : ResponseBag
    {
        $params = $request->toArray();
        $productType = new ProductType();
        $productType->setName($params['name'] ?? null);
        $errors = $this->validator->validate($productType);
        if (count($errors) > 0) {
            return new ResponseBag($errorHelper->transformErrors($errors), 422);
        }

        $this->productTypeRepository->save($productType, true);

        return new ResponseBag($productType, 201, ['productType']);
    }
}

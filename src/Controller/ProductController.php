<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Product;
use App\Helper\ResponseBag;
use App\Repository\ModelRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/product', condition: "request.headers.get('Content-Type') matches '~Application\/json~i'")]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager)
    {}

    #[Route(path: '', name: 'product_get_collection', methods: ['GET'])]
    public function get() : ResponseBag
    {
        return new ResponseBag($this->entityManager->getRepository(Product::class)->findAll(), 200, ['product']);
    }

    #[Route(path: '', name: 'product_create', methods: ['POST'])]
    public function create(Request $request) : ResponseBag
    {
        $params = $request->toArray();
        $product = new Product();
        $product->setName($params['name'] ?? null);
        $product->setPrice($params['price'] ?? null);
        if (!empty($params['model_id'])) {
            $model = $this->entityManager->getRepository(Model::class)->find($params['model_id']);
            $product->setModel($model);
        }
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new ResponseBag((string)$errors, 422);
        }

        /** @var ProductRepository $repository */
        $repository = $this->entityManager->getRepository(Product::class);
        $repository->save($product, true);

        return new ResponseBag($product, 201, ['product']);
    }

    #[Route(path: '/{productTypeId}', name: 'product_get_collection', requirements: ['productTypeId' => '\d+'], methods: ['GET'])]
    public function getByProductType(int $productTypeId) : ResponseBag
    {
        /** @var ProductRepository $repository */
        $repository = $this->entityManager->getRepository(Product::class);
        $products = $repository->findByProductTypeId($productTypeId);
        return new ResponseBag($products, 200, ['product']);
    }
}

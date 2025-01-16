<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Product;
use App\Repository\AdminRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


 class HomeController extends AbstractController{
    public function index(ProductRepository $productRepository): Response
    {
       $product = $productRepository->findAll();
         return $this->render('home/index.html.twig', [
              'product' => $product
         ]);
    }
    public function add(){
        return $this->render('home/add.html.twig');
    }
   public function store(Request $request,EntityManagerInterface $em){
      $name = $request->request->get('name');
      $category = $request->request->get('category');
      $production = $request->request->get('production_date');
      $expiry = $request->request->get('expiration_date');

      $product = new Product();
      $product->setName($name);
      $product->setCategory($category);

      $productionDate = new \DateTime($production);
      $expirationDate = new \DateTime($expiry);
  
      $product->setProductionDate($productionDate);
      $product->setExpirationDate($expirationDate);

      $em->persist($product);
      $em->flush();

      $this->addFlash('success', 'Le produit a été ajouté avec succès !');
      return $this->redirectToRoute('index');
   }
   public function delete($id, EntityManagerInterface $em, ProductRepository $productRepository){
      $product = $productRepository->find($id);
      $em->remove($product);
      $em->flush();
      $this->addFlash('success', 'Le produit a été supprimé avec succès !');
      return $this->redirectToRoute('index');
   }
   public function edit($id , ProductRepository $productRepository){
      $product = $productRepository->find($id);
      return $this->render('home/edit.html.twig', [
         'product' => $product
      ]);
   }
   public function update(Request $request, EntityManagerInterface $em){
          $id = $request->request->get("id");
          $name = $request->request->get('name');
          $category = $request->request->get('category');
          $production = $request->request->get('production_date');
          $expiry = $request->request->get('expiration_date');
     
          $product = $em->getRepository(Product::class)->find($id);
          $product->setName($name);
          $product->setCategory($category);
     
          $productionDate = new \DateTime($production);
          $expirationDate = new \DateTime($expiry);
     
          $product->setProductionDate($productionDate);
          $product->setExpirationDate($expirationDate);
     
          $em->flush();
          $this->addFlash('success', 'Le produit a été modifié avec succès !');
          return $this->redirectToRoute('index');
     }
     public function search(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->query->get('query', '');

        $products = $productRepository->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
 
        return $this->render('home/show.html.twig', [
            'products' => $products,
            'query' => $query
        ]);
    }
    public function home(){
      return $this->render('home/home.html.twig');
    }
    public function form(){
      return $this->render('home/form.html.twig');
    }
    public function admin(Request $request,EntityManagerInterface $em){
      $nom = $request->request->get('nom');
      $email = $request->request->get('email');
      $password = $request->request->get('password');

      $admin = new Admin();
     $admin->setNom($nom);
     $admin->setEmail($email);
     $admin->setPassword($password);
  
     

      $em->persist($admin);
      $em->flush();

      $this->addFlash('success', 'Admin a été ajouté avec succès !');
      return $this->redirectToRoute('index');
   }
   public function users(AdminRepository $adminRepository){
      $admins = $adminRepository->findAll();
      return $this->render('home/users.html.twig', [
         'admins' => $admins
         ]);
   }
   public function ed($id , AdminRepository $adminRepository){
      $product = $adminRepository->find($id);
      return $this->render('home/us.html.twig', [
         'product' => $product
      ]);
   }
   public function up(Request $request, EntityManagerInterface $em){
          $id = $request->request->get("id");
          $nom = $request->request->get('nom');
          $email = $request->request->get('email');
          $password = $request->request->get('password');
         
     
          $product = $em->getRepository(Admin::class)->find($id);
          $product->setNom($nom);
          $product->setEmail($email);
          $product->setPassword($password);
         
          $em->flush();
         $this->addFlash('success', 'Le dmin a été modifié avec succès !');
          return $this->redirectToRoute('index');
     }


}

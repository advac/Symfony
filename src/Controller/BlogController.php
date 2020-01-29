<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BlogController extends AbstractController
{
    public function list()
    {
         $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('blog/list.html.twig', ['posts' => $posts]);
    }

    public function show($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post) {
            // cause the 404 page not found to be displayed
            throw $this->createNotFoundException();
        }

        return $this->render('blog/show.html.twig', ['post' => $post]);
    }

    public function newPost(Request $request)
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Post'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $form->getData();
    
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('blog_list');
        }

        return $this->render("blog/addpost.html.twig", ['formpost' => $form->createView()]);
    }
} 
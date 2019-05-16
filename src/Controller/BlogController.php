<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     *
     * @return void
     * 
     * @Route("/{page}", name="blog_list", defaults={"page" : 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get("limit", 10);
        $repository =  $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                "page" => $page,
                "limit" => $limit,
                "data" => array_map(function ($item) {
                    return $this->generateUrl("blog_by_slug", ["slug" => $item->getSlug()]);
                }, $items) 
            ]
        );
    }

    /**
     *
     * @return void
     * 
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post)
    {
        return $this->json($post);
    }
    
    /**
     *
     * @return void
     * 
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost", options={"mapping":{"slug": "slug"}})
     */
    public function postBySlug(BlogPost $post)
    {
        return $this->json($post);
    }
    
    /**
     *
     * @return void
     * 
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get("serializer");

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, "json");

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     *
     * @param BlogPost $post
     * @return void
     * 
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}

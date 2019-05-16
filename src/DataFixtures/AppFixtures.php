<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\BlogPost;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle("A first post!");
        $blogPost->setPublished(new \DateTime("2019-05-16 12:00:00"));
        $blogPost->setAuthor("Bruno Rocha");
        $blogPost->setContent("Post Text");
        $blogPost->setSlug("a-first-post");

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle("A second post!");
        $blogPost->setPublished(new \DateTime("2019-05-16 12:00:00"));
        $blogPost->setAuthor("Bruno Rocha");
        $blogPost->setContent("Post Text");
        $blogPost->setSlug("a-second-post");

        $manager->persist($blogPost);

        $manager->flush();
    }
}

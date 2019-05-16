<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference("user_admin");

        $blogPost = new BlogPost();
        $blogPost->setTitle("A first post!");
        $blogPost->setPublished(new \DateTime("2019-05-16 12:00:00"));
        $blogPost->setAuthor($user);
        $blogPost->setContent("Post Text");
        $blogPost->setSlug("a-first-post");

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle("A second post!");
        $blogPost->setPublished(new \DateTime("2019-05-16 12:00:00"));
        $blogPost->setAuthor($user);
        $blogPost->setContent("Post Text");
        $blogPost->setSlug("a-second-post");

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("admin");
        $user->setEmail("brunodotcom@outlook.com");
        $user->setName("Bruno Rocha");
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                "secret123#"
            )
        );

        $this->addReference("user_admin", $user);

        $manager->persist($user);
        $manager->flush();
    }
}

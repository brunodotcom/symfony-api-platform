<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**     
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference("user_admin");

        for ($i=0; $i < 100; $i++) { 
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setAuthor($user);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);    
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i=0; $i < 100; $i++) { 
            for ($j=0; $j < rand(1, 10); $j++) { 
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference("user_admin"));
                
                $manager->persist($comment);
            }
        }

        $manager->flush($comment);
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

<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i<=10; $i++){
            $post = new Post();
            $post->setTitle($i . " Post Title");
            $post->setAuthor("By: ". $i . " - Juliana");
            $post->setBody( "Body: " . $i . " Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime blanditiis error officia quaerat rerum corporis impedit in debitis, ***** repellat. Illo ex distinctio eos eaque error iusto laboriosam, reiciendis est? Lorem ipsum dolor sit amet ***** adipisicing elit. Maxime blanditiis error officia quaerat rerum corporis impedit in debitis, explicabo repellat. Illo ex distinctio eos eaque error iusto ***** reiciendis est?");
            $manager->persist($post);
        }
        $manager->flush();
    }
}

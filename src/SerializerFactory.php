<?php


namespace App;


use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerFactory
{
    /**
     * @param ClassMetadataFactoryInterface $factory
     * @param ClassDiscriminatorResolverInterface $resolver
     * @param array $encoders
     * @return SerializerInterface
     */
    public static function createSerializer($factory, $resolver, $encoders)
    {
        $serializer = new Serializer([new ObjectNormalizer($factory, null,null,null, $resolver)], $encoders);
        // $metaFactory = 0$container->get();
        return $serializer;
    }
}

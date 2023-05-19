<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use App\Controller\ImageCoverController;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="Users")
 * @ApiResource(
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "openapi_context" = {
 *         "requestBody" = {
 *           "description" = "Create a new Users",
 *           "content" = {
 *             "application/json" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "name" = {
 *                     "type" = "string",
 *                     "description" = "The name of the Users",
 *                     "example" = "danil",
 *                   },
 *                   "image" = {
 *                     "description" = "A picture",
 *                     "type" = "integer",
 *                     "example" = 1,
 *                   },
 *                 },
 *               },
 *             },
 *             "application/ld+json" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "name" = {
 *                     "type" = "string",
 *                     "description" = "The name of the Users",
 *                     "example" = "danil",
 *                   },
 *                   "image" = {
 *                     "description" = "A picture",
 *                     "type" = "string",
 *                     "example" = "api/Image/1",
 *                   },
 *                 },
 *               },
 *             },
 *           },
 *         },
 *       },
 *     },
 *   },
 *   itemOperations={
 *     "get",
 *     "patch",
 *     "delete",
 *     "put",
 *   }
 * )
 */
#[ApiFilter(NumericFilter::class, properties: ['id'])]
#[ApiFilter(DateFilter::class, properties: ['created_at'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'created_at'], arguments: ['orderParameterName' => 'order'])]
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=70)
     * @Assert\NotBlank()
     */
    public string $name;

    /**
     * @param Image $img
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ApiProperty(iri="http://schema.org/image")
     */
    public ?Image $image = null;

    /**
     * @ORM\Column(type="datetime")
     */
    public ?\DateTime $created_at = null;
    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->created_at == null) {
            $this->created_at = new \DateTime('now');
        }
    }
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->name;
    }

}
<?php

namespace AdminBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table()
*
@ORM\Entity(repositoryClass="AdminBundle\Entity\PageRepository")
*/
class Page
{
/**
* @var integer $id
*
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
private $id;
/**
* @var string $titrePage
*
* @ORM\Column(name="titrePage", type="string", length=255)
*/
private $titrePage;
/**
* @var text $contenu
*
* @ORM\Column(name="contenu", type="text")
*/
private $contenu;
/**
* @return integer
*/
public function getId()
{
return $this->id;
}
/**
* @param string $titrePage
* @return Page
*/
public function setTitrePage($titrePage)
{
$this->titrePage = $titrePage;
return $this;
}
/**
* @return string
*/
public function getTitrePage()
{
return $this->titrePage;
}
/**
* @param string $contenu
* @return Page
*/
public function setContenu($contenu)
{
$this->contenu = $contenu;
return $this;
}
/**
* @return string
*/
public function getContenu()
{
return $this->contenu;
}
}

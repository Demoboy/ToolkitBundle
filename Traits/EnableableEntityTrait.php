<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Traits;

/**
 * Trait that implements HideableEntityInterface.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.1
 */
trait EnableableEntityTrait
{
    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool determine is the entity is enabled
     */
    protected $enabled = true;
    
    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }
    
    public function isEnabled() {
        return $this->enabled;
    }
    
    public function isDisabled() {
        return !$this->enabled;
    }   
}

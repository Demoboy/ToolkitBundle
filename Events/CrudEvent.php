<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Events;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

/**
 * Event class used when triggering an crud action
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class CrudEvent extends Event
{

    /**
     * @var The event trigger name
     */
    const EVENT = "kmjtoolkit.crud";

    /**
     * The action that is being preformed
     *
     * @var string
     */
    protected $action;

    /**
     * The entity that the action is being applied to
     *
     * @var mixed
     */
    protected $entity;

    /**
     * The form for the entity
     *
     * @var FormInterface|null
     */
    protected $form;

    /**
     * Extra variables that need to be passed to the entity.
     *
     * @var array
     */
    protected $extraVars;

    /**
     * Allows overridding of a view action, make this property not null and the entities contained within will be displayed
     * 
     * @var array|null The Entities to view
     */
    protected $entities;

    /**
     * Basic constructor
     *
     * @param string $action
     * @param mixed $entity
     * @param FormInterface $form
     * @param type $extraVars
     */
    public function __construct($action, $extraVars, &$entity, $form = null)
    {
        $this->action = $action;
        $this->entity = $entity;
        $this->form = $form;
        $this->extraVars = $extraVars;
    }

    /**
     * Get the value of The action that is being preformed
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the value of The entity that the action is being applied to
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the value of The form for the entity
     *
     * @return FormInterface|null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get the value of Extra variables that need to be passed to the entity.
     *
     * @return array
     */
    public function getExtraVars()
    {
        return $this->extraVars;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function setEntities(array $entities)
    {
        $this->entities = $entities;
        return $this;
    }
}

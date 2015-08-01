<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Controller;

use InvalidArgumentException;
use KMJ\ToolkitBundle\Events\CrudEvent;
use KMJ\ToolkitBundle\Interfaces\DeleteableEntityInterface;
use KMJ\ToolkitBundle\Interfaces\HideableEntityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Abstract class used for basic crud functions. This class will handle
 * adding, editing, deleting, hiding, viewing, and displaying single objects. 
 * The fucntions use EventListener to trigger events during the method execution,
 * allowing code to be "injected" into these methods and modify the underlying entity
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
abstract class CrudController extends Controller
{

    /**
     * Action constant for hideAction method
     */
    const ACTION_HIDE = "hide";

    /**
     * Action constant for unhideAction method
     */
    const ACTION_UNHIDE = "unhide";

    /**
     * Action constant for deleteAction method
     */
    const ACTION_DELETE = "delete";

    /**
     * Action constant for addAction method
     */
    const ACTION_ADD = "add";

    /**
     * Action constant for editAction method
     */
    const ACTION_EDIT = "edit";

    /**
     * Action constant for detailsAction method
     */
    const ACTION_DETAILS = "details";

    /**
     * Action constant for viewAction method
     */
    const ACTION_VIEW = "view";

    /**
     * Status for successful action
     */
    const STATUS_SUCCESS = "success";

    /**
     * Status for a failed action
     */
    const STATUS_FAILURE = "failure";

    /**
     * Timeline constant used to build the EventDispatcher token. Used just before form binding and validation
     */
    const TIMELINE_PRE_ACTION = "pre";

    /**
     * Timeline constant used to build the EventDispatcher token. Used just before the entity is persisted 
     */
    const TIMELINE_ENTITY_PERSIST = "persist";

    /**
     * Timeline constant used to build the EventDispatcher token. Used after successful form validation and after the entity is persisted
     */
    const TIMELINE_POST_ACTION = "post";

    /**
     * Contains any extra vars that need to be passed to events
     * @var array
     */
    protected $extraVars = array();

    /**
     * Gets the entity object for the CRUD controller.
     * 
     * @return mixed A new entity object
     */
    abstract public function getEntityClass();

    /**
     * Gets a new form type for the given entity
     * 
     * @return mixed A form type for the entity
     */
    abstract function getFormType();

    /**
     * Creates a redirect response for a given action
     * 
     * @param string $action The actions that is being performed
     * @param string $status The status of the request
     * @param string $entity The entity that has had the action performed on it
     * 
     * @return RedirectResponse A redirect response for the action
     */
    abstract function createRedirectResponse($action, $status, $entity);

    /**
     * returns a string to use for key when referencing the entity. This 
     * customizes the varible to use in twig templates
     * 
     * @param boolean $multi If true, the returned name should be plural
     * @return $string
     */
    abstract protected function getTwigEntityName($multi = false);

    /**
     * The key to use for flash messages
     * 
     * @param $status The status to get the key for
     * @return string
     */
    abstract function getFlashKey($status);

    /**
     * If the function returns false, the function calling this method will 
     * return a NotFoundHttpException, preventing execution.
     * 
     * @param string $action The action being preformed
     * @return boolean True if the current action should be allowed to run
     */
    public function allowAction($action)
    {
        return true;
    }

    /**
     * Determine if the action is allowed to be preformed on the given entity. Unlike
     * allowAction, returning false will cause a AccessDeniedException to be thrown.
     * This is a great opertuiniy to check the object against any ACL or any other requirements i.e. status
     * 
     * @param string $action The action being preformed
     * @param type $entity The entity the action will be preformed against
     * @return boolean True if the action is allowed
     */
    public function actionShouldRun($action, $entity)
    {
        return true;
    }

    /**
     * Handles adding a new entity to the db
     * 
     * @param Request $request The http request
     * @return array
     * @Route("/add")
     * @Template()
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function addAction(Request $request)
    {
        $action = self::ACTION_ADD;

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        $form = null;
        $entity = $this->getEntityClass();

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        $event = new CrudEvent($action, $this->extraVars, $entity, $form);

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        if ($this->handleEntityForm($request, $entity, $action, $form)) {
            $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);
            return $this->setFlashAndRedirect($action, $entity);
        }

        return array(
            "form" => $form->createView(),
        );
    }

    /**
     * Generates a token to use when calling an event
     * 
     * @param string $action The action being applied
     * @param string $time The time in function processing that the event is being dispatched
     * @return string
     */
    public function generateEventToken($action, $time)
    {
        return sprintf("%s.%s.%s.%s", CrudEvent::EVENT, $this->getClassName(), $action, $time);
    }

    /**
     * Hides entity from being displayed
     * 
     * @param integer $id The id of the entity to hide
     * @return RedirectResponse The response of the method
     * @throws NotFoundHttpException
     * @Route("/hide/{id}")
     */
    public function hideAction($id)
    {
        $entity = $this->getEntity($id);
        $action = self::ACTION_HIDE;

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        if (!($entity instanceof HideableEntityInterface)) {
            throw $this->createNotFoundException("Entity is not an instance of \KMJ\ToolkitBundle\Interfaces\HideableEntityInterface");
        }

        if ($entity->isHidden()) {
            throw $this->createNotFoundException("Entity is already hidden");
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        $entity->setHidden(true);
        $em = $this->getDoctrine()->getManager();

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST), $event);

        $em->persist($entity);
        $em->flush();

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);

        return $this->setFlashAndRedirect(self::ACTION_HIDE, $entity);
    }

    /**
     * Unhides entity and allows it to be displayed
     * 
     * @param integer $id The id of the entity to hide
     * @return RedirectResponse The response of the method
     * @throws NotFoundHttpException
     * @Route("/unhide/{id}")
     */
    public function unhideAction($id)
    {
        $entity = $this->getEntity($id);
        $action = self::ACTION_UNHIDE;

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        if (!($entity instanceof HideableEntityInterface)) {
            throw $this->createNotFoundException("Entity is not an instance of \KMJ\ToolkitBundle\Interfaces\HideableEntityInterface");
        }

        if (!$entity->isHidden()) {
            throw $this->createNotFoundException("Entity is not hidden");
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);
        $entity->setHidden(false);

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST), $event);
        $this->save($entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);

        return $this->setFlashAndRedirect(self::ACTION_HIDE, $entity);
    }

    /**
     * Builds a translation key for the action and status for the class
     * 
     * @param string $action The action being preformed
     * @param string $status The status of the action
     * @param string $class The class name
     * @return string
     */
    public static function buildTranslationKey($action, $status, $class)
    {
        return sprintf("%s.crud.%s.%s", $class, $action, $status);
    }

    /**
     * Removes the specified task from the db
     * 
     * @param Request $request The symfony request
     * @param integer $id The id of the entity to hide
     * @throws NotFoundHttpException
     * @Route("/delete/{id}")
     */
    public function deleteAction(Request $request, $id)
    {
        $action = self::ACTION_DELETE;

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        $entity = $this->getEntity($id);

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        if (!($entity instanceof DeleteableEntityInterface)) {
            throw $this->createNotFoundException("Entity is not an instance of \KMJ\ToolkitBundle\Interfaces\DeleteableEntityInterface");
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        $em = $this->getDoctrine()->getManager();

        foreach ($entity->removeRelated() as $r) {
            $em->remove($r);
        }

        $em->remove($entity);
        $em->flush();

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);

        return $this->setFlashAndRedirect(self::ACTION_DELETE, $entity);
    }

    /**
     * Provides an edit function of a task
     * 
     * @param Request $request The http request
     * @param integer $id The task to edit
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $action = self::ACTION_EDIT;

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        $entity = $this->getEntity($id);

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        if ($this->handleEntityForm($request, $entity, $action, $form)) {
            $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);
            return $this->setFlashAndRedirect(self::ACTION_EDIT, $entity);
        }

        return array(
            "form" => $form->createView(),
            $this->getTwigEntityName() => $entity,
        );
    }

    /**
     * Provides a detailed output of the task
     * 
     * @param Request $request The symfony request
     * @param integer $id The entity to view
     * @return array
     * @Route("/details/{id}",  requirements={"id" = "\d+"})
     * @Template()
     */
    public function detailsAction(Request $request, $id)
    {
        $action = self::ACTION_DETAILS;

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        $entity = $this->getEntity($id);

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);

        return array(
            $this->getTwigEntityName() => $entity,
        );
    }

    /**
     * Shows all non-hidden entites
     * 
     * @param Request $request The symfony request
     * @Route("/view")
     * @Template()
     */
    public function viewAction(Request $request)
    {
        $action = self::ACTION_VIEW;

        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        $entity = $this->getEntityClass();
        $entities = null;

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }

        $repo = $this->getDoctrine()->getRepository(get_class($this->getEntityClass()));

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        if ($event->getEntities() === null) {
            if ($entity instanceof HideableEntityInterface) {
                $entities = $repo->findBy(array(
                    "hidden" => false,
                ));
            } else {
                $entities = $repo->findAll();
            }
        } else {
            $entities = $event->getEntities();
        }

        $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_POST_ACTION), $event);

        return array(
            $this->getTwigEntityName(true) => $entities,
        );
    }

    /**
     * Creates and handles the entity's form. If the submitted form is valid, the entity is persisted.
     * the $form varaible is passed as reference so that a boolean value could be returned,
     * but still be able to access the form to pass to the view
     * 
     * @param Request $request The http request
     * @param mixed $entity The entity
     * @param string $action The action being performed
     * @param null $form Passed by reference to allow accessing the form
     * @return boolean True if the form was valid and persisted to the db
     */
    private function handleEntityForm(Request $request, $entity, $action, &$form = null)
    {
        $form = $this->createForm($this->getFormType(), $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $event = new CrudEvent($action, $this->extraVars, $entity, $form);
            $this->get("event_dispatcher")->dispatch($this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST), $event);

            $em->persist($entity);
            $em->flush();
            return true;
        }

        return false;
    }

    /**
     * Gets the entity from an id
     * 
     * @param integer $id The id of the object to get
     * @return mixed|null The object, null if not found
     */
    protected function getEntity($id)
    {
        return $this->getDoctrine()->getRepository(get_class($this->getEntityClass()))->find($id);
    }

    /**
     * Sets a flash message reguarding a successful action, then creates 
     * a redirect response and returns it
     * 
     * @param string $action The action that was being preformed
     * @param string $entity The entity the action was preformed on
     * @return RedirectResponse The response
     * @throws InvalidArgumentException Only thrown if there is an invalid result of createRedirectResponse
     */
    private function setFlashAndRedirect($action, $entity)
    {
        $this->addFlash($this->getFlashKey(self::STATUS_SUCCESS), self::buildTranslationKey($action, self::STATUS_SUCCESS, $this->getClassName()));

        $response = $this->createRedirectResponse($action, self::STATUS_SUCCESS, $entity);

        if (!($response instanceof RedirectResponse)) {
            throw new InvalidArgumentException("Redirect response was not received");
        }

        return $response;
    }

    /**
     * Gets the current classname as a lower-case string
     * 
     * @return string The current class name
     */
    private function getClassName()
    {
        $className = get_class($this);
        $pos = strrpos($className, '\\');
        return str_replace("controller", "", strtolower(substr($className, $pos + 1)));
    }

    /**
     * Saves an object to the database
     * @param mixed $entity The entity to be saved
     */
    protected function save($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
    }
}

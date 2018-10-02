<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Controller;

use DeepCopy\Filter\Filter;
use InvalidArgumentException;
use KMJ\ToolkitBundle\Events\CrudEvent;
use KMJ\ToolkitBundle\Interfaces\DeleteableEntityInterface;
use KMJ\ToolkitBundle\Interfaces\EnableableEntityInterface;
use KMJ\ToolkitBundle\Interfaces\HideableEntityInterface;
use KMJ\ToolkitBundle\Repository\FilterableEntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Abstract class used for basic crud functions. This class will handle
 * adding, editing, deleting, hiding, viewing, and displaying single objects.
 * The functions use EventListener to trigger events during the method execution,
 * allowing code to be "injected" into these methods and modify the underlying entity.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since  1.1
 */
abstract class CrudController extends Controller
{
    /**
     * Action constant for hideAction method.
     */
    const ACTION_HIDE = 'hide';

    /**
     * Action constant for unhideAction method.
     */
    const ACTION_UNHIDE = 'unhide';

    /**
     * Action constant for deleteAction method.
     */
    const ACTION_DELETE = 'delete';

    /**
     * Action constant for addAction method.
     */
    const ACTION_ADD = 'add';

    /**
     * Action constant for editAction method.
     */
    const ACTION_EDIT = 'edit';

    /**
     * Action constant for detailsAction method.
     */
    const ACTION_DETAILS = 'details';

    /**
     * Action constant for viewAction method.
     */
    const ACTION_VIEW = 'view';

    /**
     * Action constant for enableAction method.
     */
    const ACTION_ENABLE = 'enable';

    /**
     * Action constant for disableAction method.
     */
    const ACTION_DISABLE = 'disable';

    /**
     * Status for successful action.
     */
    const STATUS_SUCCESS = 'success';

    /**
     * Status for a failed action.
     */
    const STATUS_FAILURE = 'failure';

    /**
     * Timeline constant used to build the EventDispatcher token. Used just before form binding and validation.
     */
    const TIMELINE_PRE_ACTION = 'pre';

    /**
     * Timeline constant used to build the EventDispatcher token. Used just before the entity is persisted.
     */
    const TIMELINE_ENTITY_PERSIST = 'persist';

    /**
     * Timeline constant used to build the EventDispatcher token. Used after successful form validation
     * and after the entity is persisted.
     */
    const TIMELINE_POST_ACTION = 'post';

    /**
     * Timeline constant used to build EventDispatcher token. Event is triggered after a form has
     * been submitted but before isValid() has been called allowing custom validations.
     */
    const TIMELINE_PRE_VALID = 'pre-valid';

    /**
     * Contains any extra vars that need to be passed to events.
     *
     * @var array
     */
    protected $extraVars = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * Handles adding a new entity to the db.
     *
     * @param Request $request The http request
     *
     * @return Response
     * @Route("/add")
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function addAction(Request $request)
    {
        $this->request = $request;
        $action = self::ACTION_ADD;
        $form = null;
        $entity = $this->getEntity();

        $this->checkAction($action, $entity);

        $event = new CrudEvent($action, $this->extraVars, $entity, $form);

        $this->get('event_dispatcher')->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

        if ($event->getForm() !== null) {
            $form = $event->getForm();
        }

        if ($this->handleEntityForm($request, $entity, $action, $form)) {
            $this->get('event_dispatcher')->dispatch(
                $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
                $event
            );
            if ($event->getResponse() !== null) {
                return $event->getResponse();
            }

            $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

            return $this->setFlashAndRedirect($action, $entity);
        }

        return $this->render(
            $this->determineTemplate($action),
            array_merge(
                $this->extraVars,
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }

    /**
     * Gets the entity from an id.
     *
     * @param int $id The id of the object to get
     *
     * @return mixed|null The object, null if not found
     */
    protected function getEntity($id = null)
    {
        if ($id === null) {
            $class = $this->getEntityClass();

            if (is_object($class)) {
                @trigger_error(
                    "Use of getEntityClass returning an object is depreacted. Please use a string via ::class"
                );

                return $class;
            }

            return new $class();
        }

        return $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
    }

    /**
     * Gets the entity object for the CRUD controller.
     *
     * @return mixed A new entity object
     */
    abstract protected function getEntityClass();

    /**
     * Gets the classname of the entity as a string
     *
     * @return string
     */
    private function getEntityClassName()
    {
        $class = $this->getEntityClass();

        if (is_object($class)) {
            @trigger_error("Use of getEntityClass returning an object is depreacted. Please use a string via ::class");

            return get_class($class);
        }

        return $class;
    }

    /**
     * Determines if the action can be executed. Throws exceptions if not.
     *
     * @param string $action
     * @param mixed  $entity
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    protected function checkAction($action, $entity)
    {
        if (!$this->allowAction($action)) {
            throw $this->createNotFoundException();
        }

        if (!$this->actionShouldRun($action, $entity)) {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * If the function returns false, the function calling this method will
     * return a NotFoundHttpException, preventing execution.
     *
     * @param string $action The action being preformed
     *
     * @return bool True if the current action should be allowed to run
     */
    protected function allowAction($action)
    {
        return true;
    }

    /**
     * Determine if the action is allowed to be preformed on the given entity. Unlike
     * allowAction, returning false will cause a AccessDeniedException to be thrown.
     * This is a great opportunity to check the object against any ACL or any other requirements i.e. status.
     *
     * @param string $action The action being preformed
     * @param string $entity The entity the action will be preformed against
     *
     * @return bool True if the action is allowed
     */
    protected function actionShouldRun($action, $entity)
    {
        return true;
    }

    /**
     * Generates a token to use when calling an event.
     *
     * @param string $action The action being applied
     * @param string $time   The time in function processing that the event is being dispatched
     *
     * @return string
     */
    protected function generateEventToken($action, $time)
    {
        return sprintf('%s.%s.%s.%s', CrudEvent::EVENT, $this->getClassName(), $action, $time);
    }

    /**
     * Gets the current classname as a lower-case string.
     *
     * @return string The current class name
     */
    private function getClassName()
    {
        $className = get_class($this);
        $pos = strrpos($className, '\\');

        return str_replace('controller', '', strtolower(substr($className, $pos + 1)));
    }

    /**
     * Gets the form options to use when creating the form
     * @param $action
     *
     * @return array
     */
    protected function getFormOptions($action, $entity) {
        return [];
    }

    /**
     * Creates and handles the entity's form. If the submitted form is valid, the entity is persisted.
     * the $form varaible is passed as reference so that a boolean value could be returned,
     * but still be able to access the form to pass to the view.
     *
     * @param Request $request The http request
     * @param mixed   $entity  The entity
     * @param string  $action  The action being performed
     * @param null    $form    Passed by reference to allow accessing the form
     *
     * @return bool True if the form was valid and persisted to the db
     */
    protected function handleEntityForm(Request $request, $entity, $action, FormInterface &$form = null)
    {
        if ($form === null) {
            $form = $this->createForm($this->getFormType($action), $entity, $this->getFormOptions($action, $entity));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $event = new CrudEvent($action, $this->extraVars, $entity, $form);
            $this->get('event_dispatcher')->dispatch(
                $this->generateEventToken($action, self::TIMELINE_PRE_VALID),
                $event
            );

            if ($event->getResponse() !== null) {
                return $event->getResponse();
            }

            $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $this->get('event_dispatcher')->dispatch(
                    $this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST),
                    $event
                );

                if ($event->getResponse() !== null) {
                    return $event->getResponse();
                }

                $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

                $entityManager->persist($entity);
                $entityManager->flush();

                return true;
            }
        }

        return false;
    }

    /**
     * Gets a new form type for the given entity.
     *
     * @return mixed A form type for the entity
     */
    abstract protected function getFormType($action);

    /**
     * Sets a flash message reguarding a successful action, then creates
     * a redirect response and returns it.
     *
     * @param string $action The action that was being preformed
     * @param mixed  $entity The entity the action was preformed on
     *
     * @return RedirectResponse The response
     *
     * @throws InvalidArgumentException Only thrown if there is an invalid result of createRedirectResponse
     */
    protected function setFlashAndRedirect($action, $entity)
    {
        $this->addFlash(
            $this->getFlashKey(
                self::STATUS_SUCCESS
            ),
            $this->buildTranslationKey($action, self::STATUS_SUCCESS, $this->getClassName())
        );

        $response = $this->createRedirectResponse($action, self::STATUS_SUCCESS, $entity);

        if (!($response instanceof RedirectResponse)) {
            throw new InvalidArgumentException('Redirect response was not received');
        }

        return $response;
    }

    /**
     * The key to use for flash messages.
     *
     * @param $status The status to get the key for
     *
     * @return string
     */
    protected function getFlashKey($status)
    {
        if ($status === self::STATUS_SUCCESS) {
            return 'success';
        } else {
            return 'error';
        }
    }

    /**
     * Builds a translation key for the action and status for the class.
     *
     * @param string $action The action being preformed
     * @param string $status The status of the action
     * @param string $class  The class name
     *
     * @return string
     */
    protected function buildTranslationKey($action, $status, $class)
    {
        return sprintf('%s.crud.%s.%s', $class, $action, $status);
    }

    /**
     * Creates a redirect response for a given action.
     *
     * @param string $action The actions that is being performed
     * @param string $status The status of the request
     * @param mixed $entity The entity that has had the action performed on it
     *
     * @return RedirectResponse A redirect response for the action
     */
    abstract protected function createRedirectResponse($action, $status, $entity);

    /**
     * Determines the template to use for rendering a twig view.
     *
     * @param string $action
     *
     * @return string
     */
    protected function determineTemplate($action)
    {
        $class = get_class($this);
        $bundlePos = strpos($class, 'Bundle');
        $bundle = str_replace('\\', '', substr($class, 0, $bundlePos + 6));

        $controllerPos = strrpos($class, '\\');
        $controller = str_replace('Controller', '', substr($class, $controllerPos + 1));

        return sprintf('%s:%s:%s.html.twig', $bundle, $controller, $action);
    }

    /**
     * Hides entity from being displayed.
     *
     * @param int $id The id of the entity to hide
     *
     * @return RedirectResponse The response of the method
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     * @Route("/hide/{id}")
     */
    public function hideAction(Request $request, $id)
    {
        $this->request = $request;
        $entity = $this->getEntity($id);
        $action = self::ACTION_HIDE;

        $this->cantBeNull($entity);
        $this->checkAction($action, $entity);

        if (!($entity instanceof HideableEntityInterface)) {
            throw $this->createNotFoundException(
                sprintf('Entity is not an instance of %s', HideableEntityInterface::class)
            );
        }

        if ($entity->isHidden()) {
            throw $this->createNotFoundException('Entity is already hidden');
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entity->setHidden(true);
        $entityManager = $this->getDoctrine()->getManager();

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entityManager->persist($entity);
        $entityManager->flush();

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        return $this->setFlashAndRedirect(self::ACTION_HIDE, $entity);
    }

    /**
     * Determines if the provided entity is null and throws an
     * NotFoundHttpException if not.
     *
     * @param mixed $entity
     *
     * @throws NotFoundHttpException
     */
    protected function cantBeNull($entity)
    {
        if ($entity === null) {
            throw $this->createNotFoundException();
        }
    }

    /**
     * Unhides entity and allows it to be displayed.
     *
     * @param int $id The id of the entity to hide
     *
     * @return RedirectResponse The response of the method
     *
     * @throws NotFoundHttpException
     * @Route("/unhide/{id}")
     */
    public function unhideAction(Request $request, $id)
    {
        $this->request = $request;
        $entity = $this->getEntity($id);
        $action = self::ACTION_UNHIDE;

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        if (!($entity instanceof HideableEntityInterface)) {
            throw $this->createNotFoundException(
                sprintf('Entity is not an instance of %s', HideableEntityInterface::class)
            );
        }

        if (!$entity->isHidden()) {
            throw $this->createNotFoundException('Entity is not hidden');
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entity->setHidden(false);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken(
                $action,
                self::TIMELINE_ENTITY_PERSIST
            ),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->save($entity);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken(
                $action,
                self::TIMELINE_POST_ACTION
            ),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        return $this->setFlashAndRedirect($action, $entity);
    }

    /**
     * Saves an object to the database.
     *
     * @param mixed $entity The entity to be saved
     */
    protected function save($entity)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * Removes the specified task from the db.
     *
     * @param Request $request The symfony request
     * @param int     $id      The id of the entity to hide
     *
     * @return RedirectResponse|Response
     * @throws NotFoundHttpException
     * @Route("/delete/{id}")
     */
    public function deleteAction(Request $request, $id)
    {
        $this->request = $request;
        $action = self::ACTION_DELETE;
        $entity = $this->getEntity($id);

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        if (!($entity instanceof DeleteableEntityInterface)) {
            throw $this->createNotFoundException(
                sprintf('Entity is not an instance of %s', DeleteableEntityInterface::class)
            );
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);
        $this->get('event_dispatcher')->dispatch($this->generateEventToken($action, self::TIMELINE_PRE_ACTION), $event);

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($entity->removeRelated() as $r) {
            $entityManager->remove($r);
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        return $this->setFlashAndRedirect($action, $entity);
    }

    /**
     * Provides an edit function of a task.
     *
     * @param Request $request The http request
     * @param int     $id      The task to edit
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $this->request = $request;
        $action = self::ACTION_EDIT;
        $entity = $this->getEntity($id);
        $form = null;

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        $event = new CrudEvent($action, $this->extraVars, $entity, $form);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        if ($event->getForm() !== null) {
            $form = $event->getForm();
        }

        $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

        if ($this->handleEntityForm($request, $entity, $action, $form)) {
            $this->get('event_dispatcher')->dispatch(
                $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
                $event
            );

            if ($event->getResponse() !== null) {
                return $event->getResponse();
            }

            $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

            return $this->setFlashAndRedirect($action, $entity);
        }

        return $this->render(
            $this->determineTemplate($action),
            array_merge(
                $this->extraVars,
                [
                    'form' => $form->createView(),
                    $this->getTwigEntityName() => $entity,
                ]
            )
        );
    }

    /**
     * returns a string to use for key when referencing the entity. This
     * customizes the varible to use in twig templates.
     *
     * @param bool $multi If true, the returned name should be plural
     *
     * @return $string
     */
    abstract protected function getTwigEntityName($multi = false);

    /**
     * Provides a detailed output of the task.
     *
     * @param Request $request The symfony request
     * @param int     $id      The entity to view
     *
     * @return Response
     * @Route("/details/{id}",  requirements={"id" = "\d+"})
     */
    public function detailsAction(Request $request, $id)
    {
        $this->request = $request;
        $action = self::ACTION_DETAILS;
        $entity = $this->getEntity($id);

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        $event = new CrudEvent($action, $this->extraVars, $entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

        return $this->render(
            $this->determineTemplate($action),
            array_merge(
                $this->extraVars,
                [

                    $this->getTwigEntityName() => $entity,
                ]
            )
        );
    }

    /**
     * Shows all non-hidden entites.
     *
     * @param Request $request The symfony request
     * @param array   $filter
     * @param bool    $showFilter
     * @param int     $page
     *
     * @return Response
     * @Route("/view/{page}")
     */
    public function viewAction(Request $request, array $filter = [], bool $showFilter = true, int $page = 1)
    {
        $this->request = $request;
        $action = self::ACTION_VIEW;
        $entity = $this->getEntity();
        $entities = null;
        $filterForm = null;

        $this->checkAction($action, $entity);

        $repo = $this->getDoctrine()->getRepository($this->getEntityClassName());

        $event = new CrudEvent($action, $this->extraVars, $entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

        if ($event->getEntities() === null) {
            $entitiesPerPage = $this->getEntityResultsPerPage();

            if ($repo instanceof FilterableEntityRepository) {
                /** @var FilterableEntityRepository $repo */
                //use the default filter if no filter is provided
                $filter = $repo->configureFilter()->resolve($filter);

                if ($this->getFilterForm() !== null) {
                    //repo is correct to filter and form type is set
                    $filterForm = $this->createForm(
                        $this->getFilterForm(),
                        $filter,
                        [
                            'method' => 'GET',
                            'action' => $this->generateUrl($request->get('_route')),
                        ]
                    );

                    $filterForm->handleRequest($request);

                    if ($filterForm->isSubmitted() && $filterForm->isValid()) {
                        $filter = $filterForm->getData();
                    }
                }

                if ($entitiesPerPage !== null) {
                    $entityQb = $repo->getFilterQb($filter);
                    $paginator = $this->get('knp_paginator');
                    $entities = $paginator->paginate($entityQb, $page, $entitiesPerPage);
                } else {
                    $entities = $repo->filter($filter);
                }
            } else {
                if ($entity instanceof HideableEntityInterface) {
                    if ($request->query->get('show_hidden') !== '1') {
                        $entities = $repo->findBy(
                            [
                                'hidden' => false,
                            ]
                        );
                    } else {
                        $entities = $repo->findBy(
                            [
                                'hidden' => true,
                            ]
                        );
                    }
                } else {
                    $entities = $repo->findAll();
                }
            }

            $event->setEntities($entities);
        } else {
            $entities = $event->getEntities();
        }

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->extraVars = array_merge($this->extraVars, $event->getExtraVars());

        if (!$showFilter && $filterForm !== null) {
            $filterForm = null;
        }

        return $this->render(
            $this->determineTemplate($action),
            array_merge(
                $this->extraVars,
                [
                    $this->getTwigEntityName(true) => $entities,
                    'filterForm' => $filterForm !== null ? $filterForm->createView() : null,
                ]
            )
        );
    }

    /**
     * @return int|null
     */
    protected function getEntityResultsPerPage()
    {
        return null;
    }

    /**
     * Get the form to use for filtering view action. If returned null, filtering is disabled
     *
     * @return string|null
     */
    protected function getFilterForm()
    {
        return null;
    }

    /**
     * Enables the given entity.
     *
     * @param int $id The id of the entity to hide
     *
     * @return RedirectResponse The response of the method
     *
     * @throws NotFoundHttpException
     * @Route("/enable/{id}")
     */
    public function enableAction(Request $request, $id)
    {
        $this->request = $request;
        $entity = $this->getEntity($id);
        $action = self::ACTION_ENABLE;

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        if (!($entity instanceof EnableableEntityInterface)) {
            throw $this->createNotFoundException(
                sprintf('Entity is not an instance of %s', EnableableEntityInterface::class)
            );
        }

        if ($entity->isEnabled()) {
            throw $this->createNotFoundException('Entity is not disabled');
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entity->setEnabled(true);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->save($entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        return $this->setFlashAndRedirect($action, $entity);
    }

    /**
     * Disables the given entity.
     *
     * @param int $id The id of the entity to hide
     *
     * @return RedirectResponse The response of the method
     *
     * @throws NotFoundHttpException
     * @Route("/disable/{id}")
     */
    public function disableAction(Request $request, $id)
    {
        $this->request = $request;
        $entity = $this->getEntity($id);
        $action = self::ACTION_DISABLE;

        $this->checkAction($action, $entity);
        $this->cantBeNull($entity);

        if (!($entity instanceof EnableableEntityInterface)) {
            throw $this->createNotFoundException(
                sprintf('Entity is not an instance of %s', EnableableEntityInterface::class)
            );
        }

        if (!$entity->isEnabled()) {
            throw $this->createNotFoundException('Entity is not enabled');
        }

        $event = new CrudEvent($action, $this->extraVars, $entity);

        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_PRE_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $entity->setEnabled(false);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_ENTITY_PERSIST),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        $this->save($entity);
        $this->get('event_dispatcher')->dispatch(
            $this->generateEventToken($action, self::TIMELINE_POST_ACTION),
            $event
        );

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        return $this->setFlashAndRedirect($action, $entity);
    }
}

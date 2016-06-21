<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Traits;

use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use KMJ\ToolkitBundle\Controller\CrudController;

/**
 * Trait for adding translations to classes extending CrudController.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.1
 */
trait TranslatableControllerTrait
{
    /**
     * Gets the english name of the entity.
     *
     * @return string The description of the entity. Used to load default english translations
     */
    public static function getEntityEnglishName()
    {
        return 'Unknown';
    }

    /**
     * Gets actions for CrudController with default translations for them.
     *
     * @return array Array of actions with translations
     */
    protected static function getActions()
    {
        return [
            CrudController::ACTION_ADD => 'created',
            CrudController::ACTION_DELETE => 'removed',
            CrudController::ACTION_DETAILS => 'displayed',
            CrudController::ACTION_EDIT => 'updated',
            CrudController::ACTION_HIDE => 'hidden',
            CrudController::ACTION_VIEW => 'displayed',
            CrudController::ACTION_UNHIDE => 'made visible',
            CrudController::ACTION_ENABLE => "enabled",
            CrudController::ACTION_DISABLE => "disabled",
        ];
    }

    /**
     * Gets statues for CrudController with default translations for them.
     *
     * @return array Array of statuses with translations
     */
    protected static function getStatuses()
    {
        return [
            CrudController::STATUS_SUCCESS => 'successfully',
            CrudController::STATUS_FAILURE => 'unsuccessfully',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationMessages()
    {
        $className = get_class();
        $pos = strrpos($className, '\\');
        $class = str_replace('controller', '', strtolower(substr($className, $pos + 1)));
        $messages = [];

        $actions = self::getActions();
        $statuses = self::getStatuses();

        foreach ($actions as $action => $actionTrans) {
            foreach ($statuses as $status => $statusTrans) {
                $message = new Message(sprintf('%s.crud.%s.%s', $class, $action, $status));

                $message->setDesc(sprintf('%s was %s %s', self::getEntityEnglishName(), $actionTrans, $statusTrans));
                $message->addSource(new FileSource(__FILE__, __LINE__));
                $messages[] = $message;
            }
        }

        return $messages;
    }
}

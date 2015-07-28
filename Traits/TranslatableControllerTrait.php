<?php

namespace KMJ\ToolkitBundle\Traits;

use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use KMJ\ToolkitBundle\Controller\CrudController;

trait TranslatableControllerTrait
{

    public static function getEntityEnglishName()
    {
        return "Unknown";
    }

    protected static function getActions()
    {
        return array(
            CrudController::ACTION_ADD => "created",
            CrudController::ACTION_DELETE => "removed",
            CrudController::ACTION_DETAILS => "displayed",
            CrudController::ACTION_EDIT => "updated",
            CrudController::ACTION_HIDE => "hidden",
            CrudController::ACTION_VIEW => "displayed",
            CrudController::ACTION_UNHIDE => "unhidden",
        );
    }

    protected static function getStatuses()
    {
        return array(
            CrudController::STATUS_SUCCESS => "successfully",
            CrudController::STATUS_FAILURE => "unsuccessfully",
        );
    }

    public static function getTranslationMessages()
    {
        $className = get_class();
        $pos = strrpos($className, '\\');
        $class = str_replace("controller", "", strtolower(substr($className, $pos + 1)));
        $messages = array();

        $actions = self::getActions();
        $statuses = self::getStatuses();

        foreach ($actions as $action => $actionTrans) {
            foreach ($statuses as $status => $statusTrans) {
                $message = new Message(CrudController::buildTranslationKey($action, $status, $class));

                $message->setDesc(self::getEntityEnglishName() . " was " . $actionTrans . " " . $statusTrans);
                $message->addSource(new FileSource(__FILE__, __LINE__));
                $messages[] = $message;
            }
        }

        return $messages;
    }
}

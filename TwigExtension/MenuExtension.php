<?php
/**
 * This file is part of the BarcodeBundle.
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 */

namespace KMJ\ToolkitBundle\TwigExtension;

use InvalidArgumentException;
use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Overrides the default knp_menu_render function.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.0
 */
class MenuExtension extends Twig_Extension
{
    /**
     * The menu helper to build the menu items.
     *
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper
     * @param string $menuTemplate
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'bootstrap_menu_renderer', [$this, 'renderMenu'], ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Renders the Menu with the specified renderer.
     *
     * @param ItemInterface|string|array $menu
     * @param array                      $options
     * @param string                     $renderer
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function renderMenu($menu, array $options = [], $renderer = null)
    {
        $options = array_merge([
            'template' => 'KMJToolkitBundle:Menu:menu.html.twig',
            'currentClass' => 'active',
            'ancestorClass' => 'active',
            'childrenClasses' => 'dropdown-menu',
            'firstClass' => 'start',
            ], $options);

        if (!$menu instanceof ItemInterface) {
            $path = [];
            if (is_array($menu)) {
                if (empty($menu)) {
                    throw new InvalidArgumentException('The array cannot be empty');
                }

                $path = $menu;
                $menu = array_shift($path);
            }

            $menu = $this->helper->get($menu, $path);
        }

        $menu = $this->helper->get($menu, [], $options);
        $this->updateChildren($menu->getChildren());

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        return $this->helper->render($menu, $options, $renderer);
    }

    private function updateChildren($children)
    {
        foreach ($children as &$child) {
            $liClass = $child->getAttribute('class');

            $liClass .= ' nav-item';

            if ($child->hasChildren()) {
                $child->setChildrenAttribute('class', 'dropdown-menu');
               // $child->setLinkAttribute('class', 'nav-link nav-toggle');
                $this->updateChildren($child->getChildren());
            } else {
                $child->setLinkAttribute('class', 'nav-link');
            }

            $child->setAttribute('class', $liClass);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bootstrap_menu_renderer';
    }
}

<?php namespace KMJ\ToolkitBundle\Traits;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\Common\Util\ClassUtils;
use ReflectionClass;
use function dump;

/**
 * Description of QuickCloneTrait
 *
 * @author Kaelin Jacobson <kaelin@kaelinjacobson.com>
 * @since 1.1
 */
trait QuickCloneTrait
{

    private $allowClone = false;

    public function allowClone($clone)
    {
        $this->allowClone = $clone;
    }

    public function __clone()
    {
        if ($this->id || $this->allowClone) {
            //entity is initalized you can preform a clone on the object data            
            if ($this instanceof Proxy) {
                $class = ClassUtils::getRealClass(get_class($this));
            } else {
                $class = $this;
            }

            $rc = new ReflectionClass($class);

            foreach ($rc->getProperties() as $prop) {
                if (in_array($prop->getName(), $this->ignoreClonedProperties()) || substr($prop->getName(), 0, 2) === "__") {
                    continue;
                }

                $prop->setAccessible(true);

                if (in_array($prop->getName(), $this->nullClonedProperties())) {
                    $prop->setValue($this, null);
                }

                $value = $prop->getValue($this);

                if (is_array($value)) {
                    $newValue = [];

                    foreach ($value as $v) {
                        if ($this->allowClone && method_exists($v, "allowClone")) {
                            $v->allowClone(true);
                        }

                        $newValue[] = clone $v;
                    }

                    $prop->setValue($this, $newValue);
                } elseif (is_object($value)) {
                    if ($this->allowClone && method_exists($value, "allowClone")) {
                        $value->allowClone(true);
                    }
                    
                    $prop->setValue($this, clone $value);
                } else {
                    //dump($prop->getName());
                }
            }
        }
    }

    /**
     * Returns the names of the properties to ignore while preforming
     * a clone operation
     * @return array
     */
    private function ignoreClonedProperties()
    {
        return [];
    }

    /**
     * Returns the names of properties to set to null instead of cloning them
     * 
     * @return type
     */
    private function nullClonedProperties()
    {
        return [
            "id",
        ];
    }
}

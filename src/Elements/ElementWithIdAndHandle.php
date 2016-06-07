<?php

/**
 * @package    Spark
 * @author     Flipbox Factory <hello@flipboxfactory.com>
 * @copyright  2010-2016 Flipbox Digital Limited
 * @license    https://github.com/FlipboxFactory/Craft3-Spark/blob/master/LICENSE
 * @link       https://github.com/FlipboxFactory/Craft3-Spark
 * @since      Class available since Release 1.1.0
 */

namespace Flipbox\Craft3\Spark\Elements;

abstract class ElementWithIdAndHandle extends Element implements Interfaces\ElementWithIdInterface, Interfaces\ElementWithHandleInterface
{

    use Traits\ElementWithIdTrait, Traits\ElementWithHandleTrait {
        Traits\ElementWithIdTrait::rules as _traitRulesWithId;
        Traits\ElementWithHandleTrait::rules as _traitRulesWithHandle;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return array_merge(
            parent::rules(),
            $this->_traitRulesWithId(),
            $this->_traitRulesWithHandle()
        );

    }

}
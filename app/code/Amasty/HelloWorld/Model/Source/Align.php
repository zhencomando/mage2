<?php

namespace Amasty\HelloWorld\Model\Source;

class Align implements \Magento\Framework\Option\ArrayInterface
{
   
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'left',
                'label' => __('Left Position')
            ),
            array(
                'value' => 'center',
                'label' => __('Center Position')
            ),
            array(
                'value' => 'right',
                'label' => __('Right Position')
            )
        );
    }

   
    public function toArray()
    {
        return ['left' => __('Left Position'),
                'center' => __('Center Position'),
                'right' => __('Right Position')

        ];
    }
}
<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\QualityControl;

use Linio\SellerCenter\Model\QualityControl\QualityControls;

class QualityControlsFactory
{
    public static function make(\SimpleXMLElement $xml): QualityControls
    {
        $qualityControls = new QualityControls();

        foreach ($xml->Status->State as $item) {
            try {
                $qualityControl = QualityControlFactory::make($item);
            } catch (\Exception $e) {
                continue;
            }

            $qualityControls->add($qualityControl);
        }

        return $qualityControls;
    }
}

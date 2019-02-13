<?php

namespace Drupal\accounts\Plugin\Field\FieldFormatter;

use Drupal\address\Plugin\Field\FieldFormatter\AddressDefaultFormatter;
use Drupal\Core\Render\Element;

/**
 * Plugin implementation of the 'address_us_default' formatter.
 *
 * @FieldFormatter(
 *   id = "address_us_default",
 *   label = @Translation("Hide US"),
 *   field_types = {
 *     "address",
 *   },
 * )
 */
class AddressHideUSFormatter extends AddressDefaultFormatter {

    public static function postRender($content, array $element) {
        /** @var \CommerceGuys\Addressing\AddressFormat\AddressFormat $address_format */
        $address_format = $element['#address_format'];
        $locale = $element['#locale']; //@todo: alternative way of getting a country code?
        // Add the country to the bottom or the top of the format string,
        // depending on whether the format is minor-to-major or major-to-minor.
        if ($address_format->getCountryCode() == 'US') {
            $format_string = $address_format->getFormat();
        }/*
        elseif (Locale::matchCandidates($address_format->getLocale(), $locale)) {
            $format_string = '%country' . "\n" . $address_format->getLocalFormat();
        }*/
        else {
            $format_string = $address_format->getFormat() . "\n" . '%country';
        }

        $replacements = [];
        foreach (Element::getVisibleChildren($element) as $key) {
            $child = $element[$key];
            if (isset($child['#placeholder'])) {
                $replacements[$child['#placeholder']] = $child['#value'] ? $child['#markup'] : '';
            }
        }
        $content = self::replacePlaceholders($format_string, $replacements);
        $content = nl2br($content, FALSE);

        return $content;
    }

}

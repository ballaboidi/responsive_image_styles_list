<?php

namespace Drupal\image_styles_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\responsive_image\Entity\ResponsiveImageStyle;

class ImageStylesListController extends ControllerBase {
  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    $formatted_styles = [];

    $styles = ResponsiveImageStyle::loadMultiple();

    if ($styles)
    {
      foreach ($styles as $style) {
        
        // Get Default Image Style
        $mappings = $style->get('image_style_mappings');
        $sm_styles = $this->getStyleDimensions(ImageStyle::load($mappings[0]['image_mapping']));
        $lg_styles = $this->getStyleDimensions(ImageStyle::load($mappings[1]['image_mapping']));

        $formatted_styles[] = [
          'name' => $style->get('label'),
          'sm' => $sm_styles,
          'lg' => $lg_styles
        ];
      }
    }
    
    // dd($formatted_styles);

    return [
      '#theme' => 'list_template',
      '#image_styles' => $formatted_styles,
    ];
  }

  public function getStyleDimensions(ImageStyle $style){
    $effects = $style->getEffects()->getConfiguration();
    $dimensions = [
      'width' => array_values($effects)[0]['data']['width'],
      'height' => array_values($effects)[0]['data']['height'],
    ];
    return $dimensions;
  }
}
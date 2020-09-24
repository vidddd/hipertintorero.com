<?php

namespace Drupal\tintorero\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller routines for page example routes.
 */
class TintoreroController extends ControllerBase {

  //use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'tintorero';
  }

  /**
   * Constructs a simple page.
   *
   * The router _controller callback, maps the path
   * 'examples/page-example/simple' to this method.
   *
   * _controller callbacks return a renderable array for the content area of the
   * page. The theme system will later render and surround the content with the
   * appropriate blocks, navigation, and styling.
   */
  public function contacto() {
    return [
       '#allowed_tags' => ['iframe','div','i','h4','p',],
      '#markup' => '<div class="container contact-content">
  <div class="row">
    <div class="col-md-4">
      <div class="call-us_phone row">
        <div class="col-xs-2 col-sm-2">
          <div class="call-us_icon">
            <i class="fa fa-phone"></i>
          </div>
        </div>
        <div class="col-xs-10 col-sm-10">
          <div class="phone">
            <h4> 91 569 52 57 </h4>
            <p>hola@hipertintorero.com</p>
          </div>
        </div>
      </div>
      <div class="call-us_address row">
        <div class="col-xs-2 col-sm-2">
          <div class="call-us_icon">
            <i class="fa fa-home"></i>
          </div>
        </div>
        <div class="col-xs-10 col-sm-10">
          <div class="address">
            <h4>Santa Saturnina 2</h4>
            <p>28019 Madrid, Spain</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3613.3697525676494!2d-3.7210590901659066!3d40.39844496628265!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4227c38c55e149%3A0xdf12c3b1d9f148bd!2sHiper+Tintorero!5e0!3m2!1sen!2ses!4v1543579266443" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
  </div>
</div>',
    ];
  }

  /**
   * A more complex _controller callback that takes arguments.
   *
   * This callback is mapped to the path
   * 'examples/page-example/arguments/{first}/{second}'.
   *
   * The arguments in brackets are passed to this callback from the page URL.
   * The placeholder names "first" and "second" can have any value but should
   * match the callback method variable names; i.e. $first and $second.
   *
   * This function also demonstrates a more complex render array in the returned
   * values. Instead of rendering the HTML with theme('item_list'), content is
   * left un-rendered, and the theme function name is set using #theme. This
   * content will now be rendered as late as possible, giving more parts of the
   * system a chance to change it if necessary.
   *
   * Consult @link http://drupal.org/node/930760 Render Arrays documentation
   * @endlink for details.
   *
   * @param string $first
   *   A string to use, should be a number.
   * @param string $second
   *   Another string to use, should be a number.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   If the parameters are invalid.
   */
  public function arguments($first, $second) {
    // Make sure you don't trust the URL to be safe! Always check for exploits.
    if (!is_numeric($first) || !is_numeric($second)) {
      // We will just show a standard "access denied" page in this case.
      throw new AccessDeniedHttpException();
    }

    $list[] = $this->t("First number was @number.", ['@number' => $first]);
    $list[] = $this->t("Second number was @number.", ['@number' => $second]);
    $list[] = $this->t('The total was @number.', ['@number' => $first + $second]);

    $render_array['page_example_arguments'] = [
      // The theme function to apply to the #items.
      '#theme' => 'item_list',
      // The list itself.
      '#items' => $list,
      '#title' => $this->t('Argument Information'),
    ];
    return $render_array;
  }

}

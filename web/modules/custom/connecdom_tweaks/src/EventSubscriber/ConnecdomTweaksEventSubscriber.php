<?php
/**
 * @file
 * Contains \Drupal\connecdom_tweaks\EventSubscriber\MyEventSubscriber.
 */

namespace Drupal\connecdom_tweaks\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\connecdom_tweaks\Controller\ConnecdomTweaksController;
use Drupal\user\Entity\User;
use Drupal\Core\Url;

/**
 * Event Subscriber ConnecdomTweaksEventSubscriber.
 */
class ConnecdomTweaksEventSubscriber implements EventSubscriberInterface {

  /**
   * Code that should be triggered on event specified 
   */
  public function onLoad(FilterResponseEvent $event) {
    $user = User::load(\Drupal::currentUser()->id());
    $route_name = \Drupal::routeMatch()->getRouteName();

    if ($route_name == 'view.worklist.main' && $user->isAuthenticated() && in_array('doctor', $user->getRoles()) && !ConnecdomTweaksController::doctorAccountIsReady($user)) {
      drupal_set_message(t('Your user profile has not yet been completed yet, so you cannot be assigned to studies. <a href="@profile">Click here to complete your profile</a>.', [
        '@profile' => Url::fromRoute('entity.user.edit_form', ['user' => $user->id()])->toString(),
      ]), 'error');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // For this example I am using KernelEvents constants (see below a full list).
    $events[KernelEvents::RESPONSE][] = ['onLoad'];
    return $events;
  }

}
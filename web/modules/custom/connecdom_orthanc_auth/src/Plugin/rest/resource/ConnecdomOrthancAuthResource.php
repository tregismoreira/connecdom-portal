<?php

namespace Drupal\connecdom_orthanc_auth\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Drupal\rest\ModifiedResourceResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\connecdom_orthanc_auth\ConnecdomOrthancAuth;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "connecdom_orthanc_auth_resource",
 *   label = @Translation("Connecdom Orthanc Auth"),
 *   uri_paths = {
 *     "https://www.drupal.org/link-relations/create" = "/api/v1/orthanc_auth"
 *   }
 * )
 */
class ConnecdomOrthancAuthResource extends ResourceBase {


  /**
   *  A curent user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user instance.
   * @param Symfony\Component\HttpFoundation\Request $current_request
   *   The current request
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, LoggerInterface $logger, Request $request) {
    parent:: __construct($configuration, $plugin_id, $plugin_definition,  $serializer_formats, $logger);
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * Responds to POST requests.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data) {
    // Load body data.
    $granted = FALSE;
    $resource = isset($data['orthanc-id']) ? $data['orthanc-id'] : NULL;
    $token_key = $data['token-key'];
    $token_value = $data['token-value'];

    // Throw exception if there is no access token.
    if ($token_key != 'access_token') {
      throw new BadRequestHttpException();
    }

    // Validate token.
    $orthanc_auth = new ConnecdomOrthancAuth();
    if ($orthanc_auth->validateAccessToken($token_value, $resource)) {
      $granted = TRUE;
    }

    // Build response.
    $response = new JsonResponse([
      'granted' => $granted,
      'validity' => 0
    ]);

    return $response;
  }

}
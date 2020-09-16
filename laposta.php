<?php

namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Laposta;
use Laposta_Member;
use Laposta_Error;

/**
 * Class LapostaPlugin
 * @package Grav\Plugin
 */
class LapostaPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * Composer autoload.
     *is
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onFormProcessed' => ['onFormProcessed', 0]
        ]);
    }

    public function onFormProcessed($event)
    {
        $form = $event['form'];
        $action = $event['action'];
        $params = $event['params'];

        switch ($action) {
            case 'laposta':

                $api_key = $this->config->get('plugins.laposta.api_key');
                Laposta::setApiKey($api_key);

                $default_list_id = $this->config->get('plugins.laposta.default_list_id');
                $list_id = is_array($params) && array_key_exists('list_id', $params) ? $params['list_id'] : $default_list_id;
                $member = new Laposta_Member($list_id);

                $data = $form['data']->toArray();
                $ip = $data['ip'];
                $email = $data['email'];
                $source_url = $form['page']->url(true);
                $custom_fields = $data;

                try {
                    $result = $member->create(array(
                        'ip' => $ip,
                        'email' => $email,
                        'source_url' => $source_url,
                        'custom_fields' => $custom_fields
                    ));
                } catch (Laposta_Error $e) {
                    $error = $e->getJsonBody()['error'];

                    if (array_key_exists('code', $error) && $error['code'] == 204) {
                        return;
                    }

                    $this->grav['log']->error($e);

                    $message = $this->grav['language']->translate('PLUGIN_LAPOSTA.ERROR_MESSAGE');
                    $this->grav->fireEvent(
                        'onFormValidationError',
                        new Event([
                            'form'    => $form,
                            'message' => $message
                        ])
                    );
                    $event->stopPropagation();
                    return;
                }
        }
    }
}
